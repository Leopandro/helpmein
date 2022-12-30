<?php

declare(strict_types=1);

namespace App\Domain\Task\Gates;

use App\Domain\Client\Model\Client;
use App\Domain\Task\Model\Task;
use App\Domain\User\Model\User;
use App\Enum\UserTaskStatus;
use App\Infrastructure\Access\Gates\BaseGate;

/**
 * Гейт для редактирования пользователя
 */
class TaskSolveByClientGate extends BaseGate
{
    public static function getCode(): string
    {
        return 'task-solve';
    }

    public function __invoke(User $user, string $taskId): bool
    {
        // TODO: add roles
//        if ($this->isSuperAdmin($user)) {
//            return true;
//        }
        $task = Client::query()
            ->with(['answers' => function($query){
                $query->where('user_task.user_id','=',auth('sanctum')->id());
//                    ->whereIn('status', [UserTaskStatus::ASSIGNED,UserTaskStatus::WORK_IN_PROGRESS]);
            }])
            ->find($taskId);
        return (bool) $task;
    }
}
