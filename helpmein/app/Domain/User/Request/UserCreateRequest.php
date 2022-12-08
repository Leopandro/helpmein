<?php

declare(strict_types=1);

namespace App\Domain\User\Request;

use Illuminate\Foundation\Http\FormRequest;

class UserCreateRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'login' => 'sometimes|unique:users|string|max:255',
            'email' => 'required|unique:users|string|email|max:255',

            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
        ];
    }
}
