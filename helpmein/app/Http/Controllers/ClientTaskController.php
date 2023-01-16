<?php

namespace App\Http\Controllers;

use App\Domain\Task\Gates\TaskSolveByClientGate;
use App\Domain\Task\Gates\TaskViewByClientGate;
use App\Domain\Task\Model\Pivot\UserTask;
use App\Domain\Task\Model\Task;
use App\Domain\Task\Request\Client\TaskSolveByClientRequest;
use App\Domain\Task\Resource\ClientTaskInfoResource;
use App\Domain\User\Model\User;
use App\Domain\UserAnswer\Model\Answer;
use App\Enum\UserTaskStatus;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Spatie\QueryBuilder\QueryBuilder;

class ClientTaskController extends Controller
{
    /** Информация о задаче */
    public function info(Request $request, Task $task): JsonResponse
    {
        Gate::authorize(TaskViewByClientGate::getCode(), $task->id);
        return $this->getSuccessResponse((new ClientTaskInfoResource($task))->toArray($request));
    }

    /** Проверить решение 1 вопроса из задачи */
    public function checkAnswer(Request $request, Task $task, int $index): JsonResponse
    {
        Gate::authorize(TaskViewByClientGate::getCode(), $task->id);
        $q = $task->questions[$index]['answers'];
        $a = $request->get('answers');
        $r = ($q === $a);
        $result = $task->questions[$index]['answers'] === $request->get('answers');
        if ($result) {
            return $this->getSuccessResponse([
                'message' => 'Успешно',
            ]);
        } else {
            return $this->getSingleErrorResponse(
                'Ошибка)))',
            );
        }
    }

    /**
     * Решает задачу клиентом
     */
    public function solve(TaskSolveByClientRequest $request, Task $task): JsonResponse
    {
        Gate::authorize(TaskSolveByClientGate::getCode(), $task->id);
        DB::beginTransaction();
        try {
            /** @var UserTask $pivot */
            $answer = $task
                ->answers()
                ->where('user_id','=',auth('sanctum')->user()->id)
                ->first();
            $answer->answer = $request->get('answer');
            $answer->status = UserTaskStatus::IN_REVIEW;
            $answer->save();
            DB::commit();
        } catch (\Throwable $throwable) {
            DB::rollBack();
            return $this->getSingleErrorResponse($throwable->getMessage(), 422);
        }
        return $this->getSuccessResponse([]);
    }

    public function list(Request $request) {
        /** @var User $user */
        $user = auth('sanctum')->user();
        /** @var Builder $query */
        $tasks = QueryBuilder::for(Task::class)
            ->allowedFilters([
//                AllowedFilter::callback('task_category_id', static function (Builder $query, $value) {
//                    return $query->where('task_category_id', '=',$value);
//                }),
            ])
            ->with('answers')
            ->whereHas('answers', function ($query) use ($user) {
                $query->where('user_task.user_id', '=', $user->getAttribute('id'));
            })
            ->orderBy('id')
            ->paginate($request->get('count'));
        return $this->getListItemsResponse($tasks, ClientTaskInfoResource::class, $request);
    }
}
