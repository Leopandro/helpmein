<?php

declare(strict_types=1);

namespace App\Domain\Task\Request\Client;

use App\Domain\User\Model\User;
use Illuminate\Foundation\Http\FormRequest;

class TaskSolveByClientRequest extends FormRequest
{
    public function rules(): array
    {
        /** @var User $user */
        return [
            'answer' => 'required|string|max:2048',
        ];
    }
}
