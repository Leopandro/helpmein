<?php

declare(strict_types=1);

namespace App\Domain\Task\Gates;

use App\Domain\Task\Model\Task;
use App\Domain\User\Model\User;
use App\Infrastructure\Access\Gates\BaseGate;

/**
 * Гейт для редактирования пользователя
 */
class TaskEditByUserGate extends BaseGate
{
    public static function getCode(): string
    {
        return 'task-edit';
    }

    public function __invoke(User $user, string $taskId): bool
    {
        // TODO: add roles
        if ($this->isSuperAdmin($user)) {
            return true;
        }
        /** @var Task $task */
        $task = Task::query()->where('user_id','=',$user->id)->find($taskId);

        if ($task->answers()->count() > 0) {
            return false;
        };
        return (bool) $task;
    }
}
