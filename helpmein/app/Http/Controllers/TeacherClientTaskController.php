<?php

namespace App\Http\Controllers;

use App\Domain\Auth\Model\PasswordReset;
use App\Domain\Client\Model\Client;
use App\Domain\Task\Gates\TaskEditByUserGate;
use App\Domain\Task\Model\Task;
use App\Domain\Task\Request\TaskEditRequest;
use App\Domain\Task\Resource\TaskInfoResource;
use App\Domain\Task\Resource\UserTaskInfoResource;
use App\Domain\Task\Service\UserTaskService;
use App\Domain\User\Model\User;
use App\Enum\TaskType;
use BenSampo\Enum\Rules\EnumKey;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Gate;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class TeacherClientTaskController extends Controller
{
    public function massAssign(Request $request, UserTaskService $userTaskService): JsonResponse
    {
        if ($userTaskService->massAssign($request)) {
            return $this->getSuccessResponse([]);
        } else {
            return $this->getSingleErrorResponse("Ошибка");
        }
    }

    public function list(Request $request) {
        /** @var LengthAwarePaginator $tasks */
        $tasks = Task::buildQueryForTeacherClientTaskList($request);
        return $this->getListItemsResponse($tasks, UserTaskInfoResource::class, $request);
    }

    public function delete(Request $request) {
        /** @var Client $client */
        $client = Client::query()->find($request->get('user_id'));
        $client->tasks()->detach($request->get('task_id'));
        return $this->getSuccessResponse([]);
    }
}
