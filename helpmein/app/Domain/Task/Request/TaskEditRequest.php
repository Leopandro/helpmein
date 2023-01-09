<?php

declare(strict_types=1);

namespace App\Domain\Task\Request;

use App\Domain\User\Model\User;
use Illuminate\Foundation\Http\FormRequest;

class TaskEditRequest extends FormRequest
{
    public function rules(): array
    {
        /** @var User $user */
        return [
            'name' => 'required|string|max:127',
            'description' => 'required|string|max:127',
            'comment' => 'sometimes|string|max:255',
            'comment_client' => 'sometimes|string|max:255',
            'questions' => 'sometimes|array:title,type,answers,radioValue|max:2048',
            'type' => 'required|string|max:255',
            'task_category_id' => 'sometimes|integer',
            'difficult_level' => 'required|string|max:2',
        ];
    }
}
