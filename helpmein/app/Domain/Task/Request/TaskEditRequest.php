<?php

declare(strict_types=1);

namespace App\Domain\Task\Request;

use App\Domain\User\Model\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Fluent;
use Illuminate\Validation\Rule;

class TaskEditRequest extends FormRequest
{
    public function rules(): array
    {
        /** @var User $user */
        return [
            'name' => 'required|string|max:127',
            'description' => 'sometimes|nullable|string|max:2047',
            'comment' => 'sometimes|nullable|string|max:255',
            'comment_client' => 'sometimes|nullable|string|max:255',
            'questions' => 'sometimes|required_if:type,task|array|max:2048',
            'questions.*.title' => [Rule::when(function(Fluent $input) {
                return $input->getAttributes()["type"] === "task";
            }, 'required|string|min:1|max:128')],
            'questions.*.type' => [Rule::when(function(Fluent $input) {
                return $input->getAttributes()["type"] === "task";
            }, 'required|string|max:128')],
            'questions.*.answers' => [Rule::when(function(Fluent $input) {
                return $input->getAttributes()["type"] === "task";
            }, ['required', 'array', 'min:2', 'max:20', 'arrayChecked:1',])],
            'questions.*.radioValue' => [Rule::when(function(Fluent $input) {
                return $input->getAttributes()["type"] === "task";
            }, ['sometimes', 'nullable', 'numeric', 'max:128'])],
            'questions.*.answers.*.title' => [Rule::when(function(Fluent $input) {
                return $input->getAttributes()["type"] === "task";
            }, 'required|string|min:1|max:128')],
            'type' => 'required|string|max:255',
            'task_category_id' => 'sometimes|integer',
            'difficult_level' => 'required|string|max:2',
        ];
    }

    public function attributes(): array
    {
        return [
            'questions.*.answers.*.title' => 'Название ответа',
            'questions.*.answers' => 'Ответы на вопрос',
            'questions.*.title' => 'Название вопроса',
        ];
    }

    public function messages()
    {
        return [
            'questions.required_if' => 'Вопросы обязательны к заполнению когда тип задачи - задача',
            'questions.*.answers.min' => 'Вопрос должен содержать как минимум 2 ответа',
            'questions.*.type.required' => 'Не выбран тип вопроса',
            'questions.*.type.required' => 'Не выбран тип вопроса',
        ];
    }
}
