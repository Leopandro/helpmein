<?php

namespace App\Infrastructure\Media\Request;

use Illuminate\Foundation\Http\FormRequest;

class UploadMediaFromUrlRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'url' => 'required|url',
        ];
    }
}
