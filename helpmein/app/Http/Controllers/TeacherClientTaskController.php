<?php

namespace App\Http\Controllers;

use App\Domain\Client\Model\Client;
use App\Domain\Task\Model\Task;
use App\Domain\Task\Resource\UserTaskAssignedInfoResource;
use App\Domain\Task\Resource\UserTaskTreeAssignedInfoResource;
use App\Domain\Task\Resource\UserTaskAllInfoResource;
use App\Domain\Task\Service\UserTaskService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class TeacherClientTaskController extends Controller
{
    /** Вывод списка всех задач клиента */
    public function list(Request $request): JsonResponse {
        /** @var LengthAwarePaginator $tasks */
        $tasks = Task::buildQueryForTeacherClientTaskList($request);
        return $this->getListItemsResponse($tasks, UserTaskAssignedInfoResource::class, $request);
    }

    /** Удаления привязки задачи к клиенту */
    public function delete(Request $request): JsonResponse {
        /** @var Client $client */
        $client = Client::query()->find($request->get('user_id'));
        $client->tasks()->detach($request->get('task_id'));
        return $this->getSuccessResponse([]);
    }
}
