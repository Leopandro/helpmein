<?php

declare(strict_types=1);

namespace App\Domain\Task\Resource;

use App\Domain\Client\Model\Client;
use App\Domain\Task\Model\Task;
use App\Infrastructure\Http\Resource\JsonResource;

class TaskInfoResource extends JsonResource
{
    public function toArray($request): array
    {
        /** @var Task $task */
        $task = $this->resource;
        return [
            'id' => $task->id,
            'name' => $task->name,
            'description' => $task->description,
            'task_category_id' => $task->task_category_id,
            'comment' => $task->comment,
            'questions' => $task->questions,
            'difficult_level' => $task->difficult_level,
            'type' => $task->type,
        ];
    }
}
