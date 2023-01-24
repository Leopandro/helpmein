<?php

namespace App\Http\Controllers;

use App\Domain\Auth\Model\PasswordReset;
use App\Domain\Client\Model\Client;
use App\Domain\User\Gates\ClientEditByUserGate;
use App\Domain\User\Model\User;
use App\Domain\User\Request\UserCreateRequest;
use App\Domain\User\Request\UserEditRequest;
use App\Domain\User\Request\UserProfileEditRequest;
use App\Domain\User\Resource\Admin\AdminUserInfoWithTaskStatsResource;
use App\Domain\User\Resource\ProfileResource;
use App\Domain\User\Resource\UserInfoResource;
use App\Domain\User\UseCase\UserInvation\UserInvationEmail;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Gate;
use Spatie\Permission\Models\Role;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class UserController extends Controller
{
    public function info(Request $request, User $user): JsonResponse
    {
        Gate::authorize(ClientEditByUserGate::getCode(), $user->id);
        return $this->getSuccessResponse((new UserInfoResource($user))->toArray($request));
    }

    public function profileInfo(Request $request): JsonResponse
    {
        return $this->getSuccessResponse((new ProfileResource(auth('sanctum')->user()))->toArray($request));
    }

    public function profileEdit(UserProfileEditRequest $request): JsonResponse
    {
        $user = auth('sanctum')->user();
        $user->name = $request->get('name');
        $user->surname = $request->get('surname');

        if ($user->save()) {
            if ($request->file('avatar')) {
                $user
                    ->clearMediaCollection('avatars');
                $user
                    ->addMediaFromRequest('avatar')
                    ->toMediaCollection('avatars');
                $user->save();
            }
            return $this->getSuccessResponse([]);
        } else {
            return $this->getSingleErrorResponse("Ошибка");
        }
    }
    public function create(UserCreateRequest $request): JsonResponse
    {
        $user = new User();
        $user->login = $request->get('login');
        $user->email = $request->get('email');
        $user->name = $request->get('name');
        $user->surname = $request->get('surname');
        $user->password = '';
        if ($result = $user->save()) {
            $user->assignRole(Role::findByName('Client'));
            $id = auth('sanctum')->user()->getAuthIdentifier();
            $token = \Illuminate\Support\Str::random(32);
            $user->teachers()->attach($id, [
                'token' => $token,
                'name' => $user->name,
                'active' => false,
                'surname' => $user->surname
            ]);
            $passwordReset = PasswordReset::query()->updateOrCreate([
                'email' => $user->email
            ], [
                'token' => $token
            ]);
            Mail::to($user->email)->send(new UserInvationEmail($user, $token));
            if ($file = $request->file('avatar')) {
                $user
                    ->addMediaFromRequest('avatar')
                    ->toMediaCollection('avatars');
                $user->save();
            }
            return $this->getSuccessResponse([]);
        } else {
            return $this->getSingleErrorResponse("Ошибка");
        }
    }

    public function edit(UserEditRequest $request, User $user): JsonResponse
    {
        Gate::authorize(ClientEditByUserGate::getCode(), $user->id);
        $user->login = $request->get('login');
        $user->email = $request->get('email');

        $user->teachers()
            ->sync([
                auth('sanctum')->id() => [
                    'name' => $request->get('name'),
                    'surname' => $request->get('surname')
                ]]);
        if ($user->save()) {
            if ($file = $request->file('avatar')) {
                $user
                    ->clearMediaCollection('avatars');
                $user
                    ->addMediaFromRequest('avatar')
                    ->toMediaCollection('avatars');
                $user->save();
            }
            return $this->getSuccessResponse([]);
        } else {
            return $this->getSingleErrorResponse("Ошибка");
        }
    }

    public function delete(Request $request, User $user): JsonResponse
    {
        Gate::authorize(ClientEditByUserGate::getCode(), $user->id);
        if ($user->delete()) {
            return $this->getSuccessResponse([]);
        } else {
            return $this->getSingleErrorResponse("Ошибка");
        }
    }

    /**  */
    public function list(Request $request) {
        /** @var User $user */
        $user = auth('sanctum')->user();
        /** @var Builder $query */
        $clients = QueryBuilder::for(Client::class)
            ->allowedFilters([
                AllowedFilter::callback('active', static function (Builder $query, $value) {
                    $query->whereHas('teachers', fn(Builder $query) =>
                        $query->where('user_clients.active', '=', $value)
                    );
                }),
            ])
            ->with('teachers')
            ->whereHas('teachers', function (Builder $query) use ($request) {
                $query->where('user_clients.user_id', '=', auth('sanctum')->id());

                if ($search = $request->get('search')) {
                    $query->where('user_clients.name','ILIKE', "%$search%")
                        ->orWhere('user_clients.surname','ILIKE', "%$search%")
                        ->orWhere('email','ILIKE', "%$search%");;
                }
            })
            ->paginate($request->get('count'));
        return $this->getListItemsResponse($clients, AdminUserInfoWithTaskStatsResource::class, $request);
    }
}
