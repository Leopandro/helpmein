<?php

namespace App\Http\Controllers;

use App\Domain\Client\Model\Client;
use App\Domain\Task\Model\Task;
use App\Domain\Task\Resource\UserTaskAllInfoResource;
use App\Domain\Task\Resource\UserTaskInfoResource;
use App\Domain\Task\Service\UserTaskService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class TeacherClientTaskController extends Controller
{
    public function massAssign(Request $request, UserTaskService $userTaskService): JsonResponse
    {
        if (($count = $userTaskService->massAssign($request)) > 0) {
            return $this->getSuccessResponse([
                'message' => $count
            ]);
        } else {
            return $this->getSingleErrorResponse('Не выбраны задачи для назначения/снятия назначения');
        }
    }

    public function list(Request $request): JsonResponse {
        /** @var LengthAwarePaginator $tasks */
        $tasks = Task::buildQueryForTeacherClientTaskList($request);
        return $this->getListItemsResponse($tasks, UserTaskInfoResource::class, $request);
    }

    public function listAllWithAssign(Request $request): JsonResponse {
        /** @var LengthAwarePaginator $tasks */
        $tasks = Task::buildQueryForTeacherClientTaskAllList($request);
        return $this->getListItemsResponse($tasks, UserTaskAllInfoResource::class, $request);
    }

    public function delete(Request $request): JsonResponse {
        /** @var Client $client */
        $client = Client::query()->find($request->get('user_id'));
        $client->tasks()->detach($request->get('task_id'));
        return $this->getSuccessResponse([]);
    }
}
