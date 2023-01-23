<?php

declare(strict_types=1);

namespace App\Domain\User\Request;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;

class RemindPasswordRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'email' => 'required|exists:users|string|email|max:255',
        ];
    }

    public function all($keys = null): array
    {
        $data = parent::all($keys);
        $data['email'] = Str::lower($this->get('email'));

        return $data;
    }
}
