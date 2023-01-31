<?php

declare(strict_types=1);

namespace App\Domain\User\Request;

use App\Domain\User\Model\User;
use Illuminate\Foundation\Http\FormRequest;

class UserProfileEditRequest extends FormRequest
{
    public function rules(): array
    {
        /** @var User $user */
        $user = auth('sanctum')->user();
        return [
            'login' => 'sometimes|unique:users|string|max:255',
            'email' => 'required|unique:users,email,'.$user->id.'|string|email|max:255',
            'image' => 'sometimes|nullable|mimes:jpg,jpeg,bmp,png|file|max:2048',
            'name' => 'required|string|max:255',
            'surname' => 'required|string|max:255',
        ];
    }

    public function attributes()
    {
        return [
            'name' => 'Имя'
        ];
    }
}
