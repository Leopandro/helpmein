<?php

namespace App\Http\Controllers;

use App\Domain\Client\Model\Client;
use App\Domain\Task\Model\Task;
use App\Domain\Task\Request\TaskListWithAssignmentRequest;
use App\Domain\Task\Request\TaskMassAssignRequest;
use App\Domain\Task\Resource\UserTaskTreeAssignedInfoResource;
use App\Domain\Task\Resource\UserTaskAllInfoResource;
use App\Domain\Task\Service\UserTaskService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class TeacherClientTaskTreeController extends Controller
{
    public function massAssign(TaskMassAssignRequest $request, UserTaskService $userTaskService): JsonResponse
    {
        $message = $userTaskService->massAssign($request);
        return $this->getSuccessResponse($message);
    }

    public function listWithoutAssign(Request $request): JsonResponse {
        /** @var LengthAwarePaginator $tasks */
        $tasks = Task::buildQueryForTeacherClientTaskList($request);
        return $this->getListItemsResponse($tasks, UserTaskAllInfoResource::class, $request);
    }

    /** Загрузка всех задач со статусами */
    public function listAllWithAssign(TaskListWithAssignmentRequest $request): JsonResponse {
        /** @var LengthAwarePaginator $tasks */
        $tasks = Task::buildQueryForTeacherClientTaskAllList($request);
        return $this->getListItemsResponse($tasks, UserTaskTreeAssignedInfoResource::class, $request);
    }

    public function delete(Request $request): JsonResponse {
        /** @var Client $client */
        $client = Client::query()->find($request->get('user_id'));
        $client->tasks()->detach($request->get('task_id'));
        return $this->getSuccessResponse([]);
    }
}
