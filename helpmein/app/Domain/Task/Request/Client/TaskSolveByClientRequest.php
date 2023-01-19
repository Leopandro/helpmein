<?php

declare(strict_types=1);

namespace App\Domain\Task\Request\Client;

use App\Domain\User\Model\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Fluent;
use Illuminate\Validation\Rule;

class TaskSolveByClientRequest extends FormRequest
{
    public function rules(): array
    {
        /** @var User $user */
        return [
            'type' => 'sometimes',
            'answer' => 'sometimes|nullable|max:2048',
            'questions.*.answers.*.checkBoxValue' => [Rule::when(function(Fluent $input) {
                return $input->getAttributes()["type"] === "task";
            }, ['required', 'boolean'])],
        ];
    }
}
