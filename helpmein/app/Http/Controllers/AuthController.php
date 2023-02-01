<?php

namespace App\Http\Controllers;

use App\Domain\Auth\Model\PasswordReset;
use App\Domain\User\Model\User;
use App\Domain\User\Request\AuthenticateUserRequest;
use App\Domain\User\Request\ChangePasswordRequest;
use App\Domain\User\Request\RegisterRequest;
use App\Domain\User\Request\RemindPasswordRequest;
use App\Domain\User\Service\AuthenticationService;
use App\Domain\User\UseCase\PasswordChanged\PasswordChangedEmail;
use App\Enum\RoleType;
use App\Enum\UserTaskStatus;
use App\Infrastructure\Http\Resource\EnumResource;
use App\Infrastructure\Http\Resource\MediaResource;
use App\Infrastructure\Lang\Translator;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use JetBrains\PhpStorm\ArrayShape;
use Spatie\Permission\Models\Role;

class AuthController extends Controller
{
    /**
     * @throws \Illuminate\Auth\AuthenticationException
     */
    #[ArrayShape(['token' => "string"])]
    public function login(AuthenticateUserRequest $request, AuthenticationService $service): array
    {
        $data = $request->all();

        $user = $service->getUserByCredentials(Str::lower($data['email']), $data['password']);
        $token = $service->createUserToken($user);

        $userData = $user->attributesToArray();
        $userData['permissions'] = $user->getPermissionsViaRoles();
        $roles = [];
        foreach ($user->getRoleNames() as $role) {
            $roles[] = new EnumResource(new RoleType($role));
        }
        $userData['roles'] = $roles;
        $userData['token'] = $token;
        return $userData;
    }

    #[ArrayShape(['user' => "array"])]
    public function user(Request $request): array
    {
        /** @var User $user */
        $user = auth('sanctum')->user();
        return [
            'user' => [
                'name' => $user->name,
                'surname' => $user->middle_name,
                'email' => $user->email
            ]
        ];
    }

    public function verifyToken(Request $request, AuthenticationService $service): JsonResponse
    {
        $user = auth('sanctum')->user();
        $token = $request->bearerToken();
        $userAttributes = $user->attributesToArray();
        $userAttributes['token'] = $token;
        $userAttributes['avatar'] = $user->getFirstMedia('avatars') ? new MediaResource($user->getFirstMedia('avatars')) : null;
        return $this->getSuccessResponse($userAttributes);
    }

    public function register(RegisterRequest $request, AuthenticationService $service): JsonResponse
    {
        $user = new User();
        $user->login = Str::lower($request->get('login'));
        $user->email = Str::lower($request->get('email'));
        $user->name = $request->get('name');
        $user->surname = $request->get('surname');
        $user->password = bcrypt($request->get('password'));


        $userData = $user->attributesToArray();
        if ($user->save()) {
            $user->assignRole(Role::findByName('Guest'));
            $token = $service->createUserToken($user);
            $userData['token'] = $token;
            $userData['permissions'] = $user->getPermissionsViaRoles();
            $userData['roles'] = $user->getRoleNames();
            return $this->getSuccessResponse($userData);
        } else {
            return $this->getErrorResponse([Translator::translate("Ошибка")],422);
        }
    }

    public function remindPassword(RemindPasswordRequest $request): JsonResponse
    {
        /** @var User $user */
        $user = User::query()->where('email','=',Str::lower($request->get('email')))->firstOrFail();
        $token = Str::random(20);

        $passwordReset = PasswordReset::query()->updateOrCreate([
            'email' => $user->email
        ], [
            'token' => $token
        ]);
        Mail::to($user->email)->send(new PasswordChangedEmail($user, $token));
        if ($result = $user->save()) {
            return $this->getSuccessResponse([
                "message" =>  Translator::translate("Ссылка для изменения пароля отправлена. Если письмо не появится в течение нескольких минут, проверьте папку \"спам\".")
            ]);
        } else {
            return $this->getSingleErrorResponse(Translator::translate("Произошла ошибка отправки сообщения"),422);
        }
    }

    public function updatePassword(ChangePasswordRequest $request, AuthenticationService $service): JsonResponse
    {
        $token = $request->get('token');

        $password_reset = PasswordReset::query()->where('token','=',$token)->firstOrFail();
        /** @var User $user */
        $user = User::query()->where('email','=',$password_reset->email)->firstOrFail();
        $user->password =  bcrypt($request->get('password'));
        if ($result = $user->save()) {
            foreach ($user->teachers as $teacher) {
                $teacher->pivot->active = true;
                $teacher->pivot->save();
            }
            $token = $service->createUserToken($user);
            $userData = $user->attributesToArray();
            $userData['token'] = $token;
            return $this->getSuccessResponse($userData);
        } else {
            return $this->getErrorResponse([Translator::translate("Ошибка")],422);
        }
    }

    protected function changePassword(ChangePasswordRequest $request): JsonResponse
    {
        /** @var User $user */
        $user = User::query()->where('id','=',auth('sanctum')->user()->id)->firstOrFail();
        if (!Hash::check($request->get('currentPassword'), $user->password)) {
            throw ValidationException::withMessages([
                'currentPassword' => \App\Infrastructure\Lang\Translator::translate(Translator::translate('Текущий пароль введен неверно')),
            ]);
        }
        $user->password = bcrypt($request->get('newPassword'));
        $user->save();
        return $this->getSuccessResponse([]);
    }
}
