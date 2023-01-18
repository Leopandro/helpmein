<?php

declare(strict_types=1);

namespace App\Domain\Task\Request;

use App\Domain\User\Model\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Fluent;
use Illuminate\Validation\Rule;

class TaskListWithAssignmentRequest extends FormRequest
{
    public function rules(): array
    {
        /** @var User $user */
        return [
            'filter.user_id' => 'required|max:127',
        ];
    }

    public function attributes(): array
    {
        return [
            'filter.user_id' => 'Кому',
        ];
    }

    public function messages()
    {
        return [
            'filter.user_id.required' => 'В поле :attribute выберите кашего клиента',
        ];
    }
}
