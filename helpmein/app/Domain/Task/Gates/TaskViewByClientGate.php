<?php

declare(strict_types=1);

namespace App\Domain\Task\Gates;

use App\Domain\Client\Model\Client;
use App\Domain\User\Model\User;
use App\Infrastructure\Access\Gates\BaseGate;
use Illuminate\Database\Eloquent\Builder;

/**
 * Гейт для решения задачи
 */
class TaskViewByClientGate extends BaseGate
{
    public static function getCode(): string
    {
        return 'task-view';
    }

    public function __invoke(User $user, string $taskId): bool
    {
        if ($this->isSuperAdmin($user)) {
            return true;
        }
        $task = Client::query()
            ->with('tasks')
            ->whereHas('tasks', function(Builder $query) use ($taskId){
                $query
                    ->where('user_task.user_id', '=', auth('sanctum')->id())
                    ->where('user_task.task_id', '=', $taskId);
            })
            ->first();
        return (bool) $task;
    }
}
