<?php
namespace App\Domain\Client\Model;

use App\Domain\Task\Model\Pivot\UserTask;
use App\Domain\Task\Model\Task;
use App\Domain\User\Model\User;
use App\Domain\UserAnswer\Model\Answer;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * @property User teachers
 */
class Client extends User
{
    protected $table = 'users';

    public function teachers(): BelongsToMany
    {
        return $this
            ->belongsToMany(User::class, 'user_clients','client_id','user_id')
            ->withPivot([
                'name',
                'active',
                'surname'
            ])
            ->withTimestamps();
    }

    public function tasks(): BelongsToMany
    {
        return $this->belongsToMany(Task::class, 'user_task', 'user_id', 'task_id')
            ->using(UserTask::class)
            ->withTimestamps();
    }

    public function answers(): BelongsToMany
    {
        return $this->belongsToMany(Answer::class, 'user_task', 'user_id', 'answer_id')
            ->using(UserTask::class)
            ->withTimestamps();
    }
}
