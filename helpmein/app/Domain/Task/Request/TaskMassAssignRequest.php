<?php

declare(strict_types=1);

namespace App\Domain\Task\Request;

use App\Domain\User\Model\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Fluent;
use Illuminate\Validation\Rule;

class TaskMassAssignRequest extends FormRequest
{
    public function rules(): array
    {
        /** @var User $user */
        return [
            'selected_user' => 'required|max:127',
        ];
    }
}
