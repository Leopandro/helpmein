<?php

namespace App\Http\Controllers;

use App\Domain\Task\Gates\TaskSolveByClientGate;
use App\Domain\Task\Gates\TaskViewByClientGate;
use App\Domain\Task\Model\Task;
use App\Domain\Task\Request\Client\TaskSolveByClientRequest;
use App\Domain\Task\Resource\ClientTaskInfoResource;
use App\Domain\Task\Resource\ClientTaskResultResource;
use App\Domain\Task\Service\UserTaskService;
use App\Domain\User\Model\User;
use App\Domain\UserAnswer\Model\Answer;
use App\Enum\UserTaskStatus;
use App\Infrastructure\Lang\Translator;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Spatie\QueryBuilder\AllowedFilter;
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
                'message' => Translator::translate("Успешно"),
            ]);
        } else {
            return $this->getSingleErrorResponse(
                Translator::translate("Ошибка"),
            );
        }
    }

    /**
     * Просмотр результата решения задачи
     */
    public function result(Request $request, Task $task, UserTaskService $userTaskService): JsonResponse
    {
        $answer = $task
            ->answer()
            ->where('user_id','=',auth('sanctum')->user()->id)
            ->first();
        $task->questions = $userTaskService->getQuestionsWithMistakes($task, $answer);
        return $this->getSuccessResponse((new ClientTaskResultResource($task))->toArray($request));
    }

    /**
     * Решает задачу клиентом
     */
    public function solve(TaskSolveByClientRequest $request, Task $task, UserTaskService $userTaskService): JsonResponse
    {
        Gate::authorize(TaskSolveByClientGate::getCode(), $task->id);
        DB::beginTransaction();
        try {
            /** @var Answer $answer */
            $answer = $task
                ->answer()
                ->where('user_id','=',auth('sanctum')->user()->id)
                ->first();;
            if ($task->type->value === 'task') {
                $mistakes = $userTaskService->getMistakesCount($task, $request->get('questions'));
                $answer->mistakes = $mistakes;
                if ($mistakes > 0) {
                    $answer->status = UserTaskStatus::REASSIGNED;
                } else {
                    $answer->status = UserTaskStatus::FINISHED;
                }
            }if ($task->type->value === 'essay') {
                $answer->answer = $request->get('answer')['answer'];
                $answer->status = UserTaskStatus::IN_REVIEW;
            }
            $answer->questions = $request->get('questions');
            $answer->save();
            DB::commit();
        } catch (\Throwable $exception) {
            DB::rollBack();
            throw $exception;
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
                AllowedFilter::callback('new', static function (Builder $query) {
                    return $query->withWhereHas('answer', fn ($query) =>
                        $query->where('answer.status','=',UserTaskStatus::ASSIGNED)
                    );
                }),
                AllowedFilter::callback('10day', static function (Builder $query) {
                    return $query->withWhereHas('answer', fn ($query) =>
                        $query
                            ->whereDate('answer.updated_at', '>', Carbon::now()->subDays(10))
                    );
                }),
                AllowedFilter::callback('all', static function (Builder $query) {
                    return $query->withWhereHas('answer', fn ($query) =>
                        $query
                    );
                }),
            ])
            ->select('task.*')
            ->leftJoin('user_task','user_task.task_id','=','task.id')
            ->leftJoin('answer','answer.user_task_id','=','user_task.id')
            ->where('user_task.user_id', '=', $user->getAttribute('id'))
            ->orderBy('answer.created_at', 'desc')
            ->paginate($request->get('count'));
        return $this->getListItemsResponse($tasks, ClientTaskInfoResource::class, $request);
    }
}
