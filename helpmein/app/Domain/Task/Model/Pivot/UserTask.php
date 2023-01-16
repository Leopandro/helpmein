<?php
namespace App\Domain\Task\Model\Pivot;

use App\Domain\UserAnswer\Model\Answer;
use App\Enum\UserTaskStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\Pivot;

/**
 * @property integer id
 */
class UserTask extends Pivot
{
    protected $table = 'user_task';

    protected $primaryKey = 'id';

    public $timestamps = false;

    protected $appends = ['id'];

    protected $guarded = [];

    public function answer(): HasOne
    {
        return $this->hasOne(Answer::class,'user_task_id','id');
    }
}
