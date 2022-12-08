<?php

namespace App\Http\Controllers;

use App\Domain\Auth\Model\PasswordReset;
use App\Domain\User\Gates\UserEditGate;
use App\Domain\User\Model\User;
use App\Domain\User\Request\UserCreateRequest;
use App\Domain\User\Request\UserEditRequest;
use App\Domain\User\Resource\UserInfoResource;
use App\Domain\User\UseCase\UserInvation\UserInvationEmail;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Gate;

class UserController extends Controller
{
    public function info(Request $request, User $user): JsonResponse
    {
        return $this->getSuccessResponse((new UserInfoResource($user))->toArray($request));
    }
    public function create(UserCreateRequest $request): JsonResponse
    {
        $user = new User();
        $user->login = $request->get('login');
        $user->email = $request->get('email');
        $user->name = $request->get('first_name');
        $user->surname = $request->get('last_name');
        $user->password = '';
        if ($result = $user->save()) {
            $id = auth('sanctum')->user()->getAuthIdentifier();
            $token = \Illuminate\Support\Str::random(32);
            $user->teachers()->attach($id, ['token' => $token]);

            $passwordReset = PasswordReset::query()->updateOrCreate([
                'email' => $user->email
            ], [
                'token' => $token
            ]);
            Mail::to($user->email)->send(new UserInvationEmail($user, $token));
            return $this->getSuccessResponse([]);
        } else {
            return $this->getSingleErrorResponse("Ошибка");
        }
    }

    public function edit(UserEditRequest $request, User $user): JsonResponse
    {
        Gate::authorize(UserEditGate::getCode(), $user->id);
        $user->login = $request->get('login');
        $user->email = $request->get('email');
        $user->name = $request->get('first_name');
        $user->surname = $request->get('last_name');
        if ($result = $user->save()) {
            return $this->getSuccessResponse([]);
        } else {
            return $this->getSingleErrorResponse("Ошибка");
        }
    }

    public function list(Request $request) {
        /** @var User $user */
        $user = auth('sanctum')->user();
        /** @var Builder $clients */
        $clients = User::query();
        $clients
            ->whereHas('teachers', function (Builder $query) {
                $query->where('user_id', '=', auth('sanctum')->id());
            });
        if ($search = $request->get('search')) {
            $clients->where(function($clients) use ($search) {
                $clients->where('name','ILIKE', "%$search%")
                    ->orWhere('surname','ILIKE', "%$search%")
                    ->orWhere('email','ILIKE', "%$search%");
            });
        }
        return $clients->get();
    }
}
