<?php

declare(strict_types=1);

namespace App\Domain\User\Request;

use App\Infrastructure\Lang\Translator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;

class RemindPasswordRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'email' => 'required|email|exists:users|string|max:255',
        ];
    }

    public function all($keys = null): array
    {
        $data = parent::all($keys);
        $data['email'] = Str::lower($this->get('email'));

        return $data;
    }

    public function attributes(): array
    {
        return [
            'email' => 'e-mail',
        ];
    }

    public function messages()
    {
        return [
            'email.exists' => Translator::translate('Пользователь с таким :attribute не зарегистрирован', [
                'attribute' => 'e-mail'
            ]),
            'email.email' => Translator::translate('Значение для поля :attribute некорректно', [
                'attribute' => 'e-mail'
            ])
        ];
    }
}
