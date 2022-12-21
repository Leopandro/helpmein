<?php

declare(strict_types=1);

namespace App\Domain\User\Request;

use Illuminate\Foundation\Http\FormRequest;

/** Запрос на изменение пароля пользователя */
class ChangePasswordRequest extends FormRequest
{

    public function rules(): array
    {
        return [
            'token' => 'required|string',
            'password' => sprintf('required|string|min:%d', config('settings.password_min_length')),
            'password_confirm' => sprintf('required|string|min:%d|same:password', config('settings.password_min_length')),
        ];
    }
}
