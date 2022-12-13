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
            'avatar' => 'sometimes|base64dimensions:min_width=100,min_height=200,max_width=1980,max_height:1080|base64max:1000',
            'name' => 'required|string|max:255',
            'surname' => 'required|string|max:255',
        ];
    }
}
