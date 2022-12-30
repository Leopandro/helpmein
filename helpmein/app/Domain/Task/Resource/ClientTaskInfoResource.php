<?php

declare(strict_types=1);

namespace App\Domain\Task\Resource;

use App\Domain\Task\Model\Task;
use App\Enum\TaskType;
use App\Enum\UserTaskStatus;
use App\Infrastructure\Http\Resource\EnumResource;
use App\Infrastructure\Http\Resource\JsonResource;

class ClientTaskInfoResource extends JsonResource
{
    public function toArray($request): array
    {
        /** @var Task $task */
        $task = $this->resource;
        $type = new EnumResource($task->type);
        if (isset($task->clients->first()->pivot->answer->status)) {
            $status = new EnumResource($task->clients->first()->pivot->answer->status);
        } else {
            $status = new EnumResource(new UserTaskStatus(UserTaskStatus::ASSIGNED));
        }
        return [
            'id' => $task->id,
            'name' => $task->name,
            'status' => $status,
            'description' => $task->description,
            'task_category_id' => $task->task_category_id,
            'comment' => $task->comment,
            'comment_client' => $task->comment_client,
            'questions' => $task->questions,
            'difficult_level' => $task->difficult_level,
            'type' => $type,
        ];
    }
}
