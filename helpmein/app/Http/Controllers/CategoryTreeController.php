<?php

namespace App\Http\Controllers;

use App\Domain\Auth\Model\PasswordReset;
use App\Domain\Client\Model\Client;
use App\Domain\TaskCategory\Model\TaskCategory;
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
use Spatie\Permission\Guard;
use Spatie\Permission\Models\Role;

class CategoryTreeController extends Controller
{
    public function list(Request $request) {
        /** @var User $user */
        $user = auth('sanctum')->user();
        /** @var TaskCategory $categoryParent */
        $categoryParent = TaskCategory::query()
            ->whereNull('parent_id')
            ->where('user_id','=',$user->id)
            ->with('descendants')
            ->firstOrFail();
        $tree = TaskCategory::descendantsAndSelf($categoryParent->id)->toTree()->first();;
        return $tree->toArray();
    }
}
