<?php

declare(strict_types=1);

namespace App\Domain\Task\Resource;

use App\Domain\Task\Model\Task;
use App\Domain\TaskCategory\Model\TaskCategory;
use App\Enum\UserTaskStatus;
use App\Infrastructure\Http\Resource\EnumResource;
use App\Infrastructure\Http\Resource\JsonResource;

class UserTaskAllInfoResource extends JsonResource
{
    public function toArray($request): array
    {
        /** @var Task $task */
        $task = $this->resource;
        $type = new EnumResource($task->type);
        $taskCategory = TaskCategory::query()->find($task->task_category_id);
        $categories = $this->getTaskCategoriesRecursively($taskCategory);
        $categoriesText = $this->parseTaskCategoriesArray($categories);
        return [
            'id' => $task->id,
            'name' => $task->name,
//            'status' => new EnumResource($task->answer->status),
            'answer_count' => $task->answer_count,
            'description' => $task->description,
            'task_category_id' => $task->task_category_id,
            'task_category' => $categoriesText,
            'comment' => $task->comment,
            'comment_client' => $task->comment_client,
            'questions' => $task->questions,
            'difficult_level' => $task->difficult_level,
            'type' => $type,
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
