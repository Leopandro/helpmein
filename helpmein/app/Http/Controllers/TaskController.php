<?php

namespace App\Http\Controllers;

use App\Domain\Auth\Model\PasswordReset;
use App\Domain\Client\Model\Client;
use App\Domain\Task\Gates\TaskEditByUserGate;
use App\Domain\Task\Gates\TaskInfoByUserGate;
use App\Domain\Task\Model\Task;
use App\Domain\Task\Request\TaskEditRequest;
use App\Domain\Task\Resource\TaskInfoResource;
use App\Domain\User\Model\User;
use App\Enum\TaskType;
use BenSampo\Enum\Rules\EnumKey;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class TaskController extends Controller
{
    public function info(Request $request, Task $task): JsonResponse
    {
        Gate::authorize(TaskInfoByUserGate::getCode(), $task->id);
        return $this->getSuccessResponse((new TaskInfoResource($task))->toArray($request));
    }

    public function create(TaskEditRequest $request): JsonResponse
    {
        $task = new Task();
        $task->name = $request->get('name');
        $task->description = $request->get('description');
        $task->comment_client = $request->get('comment_client');
        $task->comment = $request->get('comment');
        $task->type = new TaskType($request->get('type'));
        $task->questions = $request->get('questions');
        $task->difficult_level = $request->get('difficult_level');
        $task->task_category_id = $request->get('task_category_id');
        $task->user_id = auth('sanctum')->id();
        if ($result = $task->save()) {
            return $this->getSuccessResponse([]);
        } else {
            return $this->getSingleErrorResponse("Ошибка");
        }
    }

    public function edit(TaskEditRequest $request, Task $task): JsonResponse
    {
        Gate::authorize(TaskEditByUserGate::getCode(), $task->id);
        $task->name = $request->get('name');
        $task->description = $request->get('description');
        $task->comment_client = $request->get('comment_client');
        $task->comment = $request->get('comment');
        $task->type = new TaskType($request->get('type'));
        $task->questions = $request->get('questions');
        $task->difficult_level = $request->get('difficult_level');
        $task->task_category_id = $request->get('task_category_id');
        $task->user_id = auth('sanctum')->id();

        if ($task->save()) {
            return $this->getSuccessResponse([]);
        } else {
            return $this->getSingleErrorResponse("Ошибка");
        }
    }

    public function delete(Request $request, Task $task): JsonResponse
    {
        Gate::authorize(TaskEditByUserGate::getCode(), $task->id);
        if ($task->delete()) {
            return $this->getSuccessResponse([]);
        } else {
            return $this->getSingleErrorResponse("Ошибка");
        }
    }

    public function list(Request $request) {
        /** @var User $user */
        $user = auth('sanctum')->user();
        /** @var Builder $query */
        $tasks = QueryBuilder::for(Task::class)
            ->allowedFilters([
                AllowedFilter::callback('task_category_id', static function (Builder $query, $value) {
                    return $query->where('task_category_id', '=',$value);
                }),
            ])
            ->with('answers')
            ->orderBy('id')
            ->paginate($request->get('count'));
        return $this->getListItemsResponse($tasks, TaskInfoResource::class, $request);
    }
}
