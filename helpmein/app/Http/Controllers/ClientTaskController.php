<?php

namespace App\Http\Controllers;

use App\Domain\Task\Gates\TaskEditByUserGate;
use App\Domain\Task\Gates\TaskSolveByClientGate;
use App\Domain\Task\Model\Task;
use App\Domain\Task\Resource\ClientTaskInfoResource;
use App\Domain\Task\Resource\TaskInfoResource;
use App\Domain\User\Model\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Spatie\QueryBuilder\QueryBuilder;

class ClientTaskController extends Controller
{
    public function info(Request $request, Task $task): JsonResponse
    {
        Gate::authorize(TaskSolveByClientGate::getCode(), $task->id);
        return $this->getSuccessResponse((new TaskInfoResource($task))->toArray($request));
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
