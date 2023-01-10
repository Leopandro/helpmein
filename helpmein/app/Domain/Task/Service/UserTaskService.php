<?php
namespace  App\Domain\Task\Service;

use App\Domain\Client\Model\Client;
use App\Domain\Task\Model\Pivot\UserTask;
use Illuminate\Http\Request;

class UserTaskService
{
    public function massAssign(Request $request): bool {
        $selectedItems = $request->get('selected_items');
        /** @var Client $client */
        $client = Client::query()->find($request->get('selected_user'));
        foreach ($selectedItems as $taskId => $value) {
            if (!$client->tasks()->wherePivot('task_id','=',$taskId)->wherePivotNotNull('answer_id')->first()) {
                if ($value === true) {
                    $client->tasks()->syncWithoutDetaching([$taskId]);
                }
                if ($value === false) {
                    $client->tasks()->detach($taskId);
                }
            }
        }
        return true;
    }
}
