<?php

namespace App\Http\Controllers;

use App\Domain\Client\Model\Client;
use App\Domain\Task\Model\Task;
use App\Domain\Task\Request\Admin\AdminTaskDeclineRequest;
use App\Domain\Task\Resource\Admin\AdminTaskInfoResource;
use App\Domain\Task\Resource\AdminUserTaskAssignedInfoResource;
use App\Domain\UserAnswer\Model\Answer;
use App\Enum\UserTaskStatus;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class TeacherClientTaskController extends Controller
{
    /** Вывод списка всех задач клиента */
    public function info(Request $request, Task $task): JsonResponse {
        /** @var LengthAwarePaginator $tasks */
        return $this->getSuccessResponse((new AdminTaskInfoResource($task))->toArray($request));
    }

    /** Прием ответа клиента */
    public function accept(Request $request, Answer $answer): JsonResponse {
        /** @var LengthAwarePaginator $tasks */
        $answer->status = UserTaskStatus::FINISHED;
        $answer->save();
        return $this->getSuccessResponse([]);
    }

    /** Вывод списка всех задач клиента */
    public function decline(AdminTaskDeclineRequest $request, Answer $answer): JsonResponse {
        /** @var LengthAwarePaginator $tasks */
        $answer->status = UserTaskStatus::REASSIGNED;
        $answer->teacher_comment = $request->get('teacher_comment');
        $answer->save();
        return $this->getSuccessResponse([]);
    }

    /** Вывод списка всех задач клиента */
    public function list(Request $request): JsonResponse {
        /** @var LengthAwarePaginator $tasks */
        $tasks = Task::buildQueryForTeacherClientTaskList($request);
        return $this->getListItemsResponse($tasks, AdminUserTaskAssignedInfoResource::class, $request);
    }

    /** Удаления привязки задачи к клиенту */
    public function delete(Request $request): JsonResponse {
        /** @var Client $client */
        $client = Client::query()->find($request->get('user_id'));
        $client->tasks()->detach($request->get('task_id'));
        return $this->getSuccessResponse([]);
    }
}
