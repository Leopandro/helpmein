<?php

namespace App\Infrastructure\Media\Request;

use Illuminate\Foundation\Http\FormRequest;

class UploadMediaRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'image' => 'required|photoData',
        ];
    }
}
