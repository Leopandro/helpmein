<?php
namespace  App\Domain\Task\Service;

use App\Domain\Client\Model\Client;
use App\Domain\Task\Model\Pivot\UserTask;
use App\Domain\Task\Model\Task;
use App\Domain\UserAnswer\Model\Answer;
use App\Enum\UserTaskStatus;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Http\Request;

class UserTaskService
{
    public function massAssign(Request $request): array {
        $selectedItems = $request->get('selected_items');
        /** @var Client $client */
        $client = Client::query()->find($request->get('selected_user'));
        $count = 0;
        $unAssigned = 0;
        foreach ($selectedItems as $taskId => $assign) {
//            $item = Task::query()
//                ->with('answers')
//                ->whereHas('answers', function ($query) use ($client, $taskId) {
//                    $query->where('user_task.user_id', '=', $client->getAttribute('id'))
//                        ->where('user_task.task_id', '=', $taskId);
//                })
//                ->first();
//            if (!$item) {
                if ($assign) {
                    if (!UserTask::query()
                        ->where('user_id','=',$client->id)
                        ->where('task_id','=',$taskId)
                        ->first()
                    ) {
                        $userTaskId = UserTask::query()->firstOrCreate([
                            'user_id' => $client->id,
                            'task_id' => $taskId
                        ]);
                        $userTask = UserTask::query()
                            ->where('user_id', '=', $client->id)
                            ->where('task_id', '=', $taskId)
                            ->first();
                        Answer::query()->create([
                            'status' => UserTaskStatus::ASSIGNED,
                            'user_task_id' => $userTask->id
                        ]);
                        $count += 1;
                    }

                } else {
                    $r = $client->tasks()->detach($taskId);
                    if ($r) {
                        $unAssigned += $r;
                    }
                }
//            } else {
//                $x = 1;
//            }
        }
        if ($count === 0 && $unAssigned === 0) {
            throw new \Exception("Не выбраны задачи для назначения/снятия назначения");
        }
        return [
            "message" => "Снято назначений с $unAssigned, задач назначено $count задач",
        ];
    }
}
