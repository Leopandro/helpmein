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
                if ($assign) {
                    if (!UserTask::query()
                        ->where('user_id','=',$client->id)
                        ->where('task_id','=',$taskId)
                        ->first()
                    ) {
                        $userTask = UserTask::query()->firstOrCreate([
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
        }
        if ($count === 0 && $unAssigned === 0) {
            throw new \Exception("Не выбраны задачи для назначения/снятия назначения");
        }
        return [
            "message" => "Снято назначений с $unAssigned, задач назначено $count задач",
        ];
    }

    public function getMistakesCount(Task $task, array $answers)
    {
        $mistakes = 0;
        if (!$task->questions) {
            return $mistakes;
        }
        $taskQuestions = $task->questions;
        foreach ($taskQuestions as $key => $question) {
            if ($question['radioValue'] !== $answers[$key]['radioValue']) {
                $mistakes++;
            }
            foreach ($question['answers'] as $answerKey => $answer) {
                $x = 1;
                if ($answer['checkBoxValue'] !== $answers[$key]['answers'][$answerKey]['checkBoxValue']) {
                    $mistakes++;
                }
            }
        }
        return $mistakes;
    }

    public function getQuestionsWithMistakes(Task $task, Answer $answer): array
    {
        $taskQuestions = $task->questions;
        $taskAnswers = $answer->questions;
        foreach ($taskQuestions as $questionNumber => $question) {
            foreach ($question['answers'] as $answerNumber => $answer) {
                $answerValue = $taskAnswers[$questionNumber]['answers'][$answerNumber]['checkBoxValue'];
                $questionValue = $answer['checkBoxValue'];
                if ($answerValue === false) {
                    if ($questionValue === false) {
                        $taskQuestions[$questionNumber]['answers'][$answerNumber]['success'] = null;
                    }
                    if ($questionValue === true) {
                        $taskQuestions[$questionNumber]['answers'][$answerNumber]['success'] = true;
                    }
                }
                if ($answerValue === true) {
                    if ($questionValue === false) {
                        $taskQuestions[$questionNumber]['answers'][$answerNumber]['success'] = false;
                    }
                    if ($questionValue === true) {
                        $taskQuestions[$questionNumber]['answers'][$answerNumber]['success'] = true;
                    }
                }
                if ($question['type'] === "radio" && $answerValue) {
                    $taskQuestions[$questionNumber]['radioValue'] = $answerNumber;
                }
                $taskQuestions[$questionNumber]['answers'][$answerNumber]['checkBoxValue'] = $answerValue;
            }
        }
        return $taskQuestions;
    }
}
