<?php

declare(strict_types=1);

namespace App\Domain\User\Request;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;

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

    public function all($keys = null): array
    {
        $data = parent::all($keys);
        $data['login'] = Str::lower($this->get('login'));
        $data['email'] = Str::lower($this->get('email'));

        return $data;
    }

    public function attributes()
    {
        return [
            'name' => __('first_name')
        ];
    }
}
