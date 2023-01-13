<?php

declare(strict_types=1);

namespace App\Domain\Task\Gates;

use App\Domain\Client\Model\Client;
use App\Domain\User\Model\User;
use App\Infrastructure\Access\Gates\BaseGate;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Pivot;

/**
 * Гейт для решения задачи
 */
class TaskSolveByClientGate extends BaseGate
{
    public static function getCode(): string
    {
        return 'task-solve';
    }

    public function __invoke(User $user, string $taskId): bool
    {
        /** @var Client $task */
        $client = Client::query()
            ->with('tasks')
            ->whereHas('tasks', function(Builder $query) use ($taskId){
                $query
                    ->where('user_task.user_id', '=', auth('sanctum')->id())
                    ->where('user_task.task_id', '=', $taskId);
            })
            ->first();
        if ($answer =
            $client
                ->answers()
                ->where('user_id','=',11)
                ->where('task_id',$taskId)
                ->first())
        {
            return in_array($answer->status,['assigned', 'reassigned', 'in_review']);
        }
        return (bool) $client;
    }
}
