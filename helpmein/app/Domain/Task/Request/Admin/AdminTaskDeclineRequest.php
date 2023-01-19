<?php

declare(strict_types=1);

namespace App\Domain\Task\Request\Admin;

use App\Domain\User\Model\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Fluent;
use Illuminate\Validation\Rule;

class AdminTaskDeclineRequest extends FormRequest
{
    public function rules(): array
    {
        /** @var User $user */
        return [
            'teacher_comment' => 'required|max:127',
        ];
    }
}
