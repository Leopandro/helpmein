<?php

namespace App\Http\Controllers;

use App\Domain\Task\Gates\TaskEditByUserGate;
use App\Domain\Task\Gates\TaskSolveByClientGate;
use App\Domain\Task\Model\Task;
use App\Domain\Task\Request\Client\TaskSolveByClientRequest;
use App\Domain\Task\Resource\ClientTaskInfoResource;
use App\Domain\Task\Resource\TaskInfoResource;
use App\Domain\User\Model\User;
use App\Domain\UserAnswer\Model\UserAnswer;
use App\Enum\UserTaskStatus;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Spatie\QueryBuilder\QueryBuilder;

class ClientTaskController extends Controller
{
    public function info(Request $request, Task $task): JsonResponse
    {
        Gate::authorize(TaskSolveByClientGate::getCode(), $task->id);
        return $this->getSuccessResponse((new TaskInfoResource($task))->toArray($request));
    }

    public function solve(TaskSolveByClientRequest $request, Task $task): JsonResponse
    {
        Gate::authorize(TaskSolveByClientGate::getCode(), $task->id);
        DB::beginTransaction();
        try {
            $userAnswer = UserAnswer::query()->firstOrCreate([
                'user_id' => auth('sanctum')->user()->id,
                'task_id' => $task->id
            ]);
            if (!in_array($userAnswer->status, [
                UserTaskStatus::ASSIGNED,
                UserTaskStatus::IN_REVIEW,
                UserTaskStatus::REASSIGNED,
            ])) {
                throw
            }
            $userAnswer->status = UserTaskStatus::IN_REVIEW;
            $userAnswer->answer = $request->get('answer');
            $userAnswer->save();
            $pivot = $task->clients()->where('user_id','=',auth('sanctum')->user()->id)->first()->pivot;
            $pivot->answer_id = $userAnswer->id;
            $pivot->save();
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
            ->with('clients')
            ->whereRelation( 'clients', 'user_id', '=', auth('sanctum')->id())
            ->orderBy('id')
            ->paginate($request->get('count'));
        return $this->getListItemsResponse($tasks, ClientTaskInfoResource::class, $request);
    }
}
