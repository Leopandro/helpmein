<?php

declare(strict_types=1);

namespace App\Domain\User\Request;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'login' => 'sometimes|unique:users|string|max:255',
            'email' => 'required|unique:users|string|email|max:255',
            'password' => 'required|string|min:4|max:255',
            'password_confirmation' => 'required|same:password|string|min:4|max:255',

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
