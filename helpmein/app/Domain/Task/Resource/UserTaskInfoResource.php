<?php

declare(strict_types=1);

namespace App\Domain\Task\Resource;

use App\Domain\Task\Model\Task;
use App\Domain\TaskCategory\Model\TaskCategory;
use App\Enum\UserTaskStatus;
use App\Infrastructure\Http\Resource\EnumResource;
use App\Infrastructure\Http\Resource\JsonResource;

class UserTaskInfoResource extends JsonResource
{
    public function toArray($request): array
    {
        /** @var Task $task */
        $task = $this->resource;
        $type = new EnumResource($task->type);
        $pivot = $task->clients?->first()?->pivot;
        $status = ($pivot) ?
            ( $pivot->answer_id ? new EnumResource($pivot->answer->status) : new EnumResource(new UserTaskStatus(UserTaskStatus::ASSIGNED)))
            : new EnumResource(new UserTaskStatus(UserTaskStatus::ASSIGNED));
        $taskCategory = TaskCategory::query()->find($task->task_category_id);
        $categories = $this->getTaskCategoriesRecursively($taskCategory);
        $categoriesText = $this->parseTaskCategoriesArray($categories);
        return [
            'id' => $task->id,
            'name' => $task->name,
            'answer' => $task->answers->first(),
            'status' => $status,
            'description' => $task->description,
            'task_category_id' => $task->task_category_id,
            'task_category' => $categoriesText,
            'comment' => $task->comment,
            'comment_client' => $task->comment_client,
            'questions' => $task->questions,
            'difficult_level' => $task->difficult_level,
            'type' => $type,
            'created_at' => $pivot->created_at
        ];
    }

    public function getTaskCategoriesRecursively($taskCategory): array
    {
        $categoryNames = [];
        while ($taskCategory->parent) {
            $categoryNames[] = $taskCategory->name;
            $taskCategory = $taskCategory->parent;
        }
        $categoryNames = array_reverse($categoryNames);
        return $categoryNames;
    }

    public function parseTaskCategoriesArray(array $categories): string
    {
        return implode(" -> ", $categories);
    }
}
