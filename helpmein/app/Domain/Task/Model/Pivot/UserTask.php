<?php
namespace App\Domain\Task\Model\Pivot;

use App\Domain\UserAnswer\Model\Answer;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\Pivot;

/**
 * @property integer id
 */
class UserTask extends Pivot
{
    protected $table = 'user_task';

    protected $primaryKey = 'id';
    protected $guarded = [];

    public $timestamps  = false;

    public function answer(): HasOne
    {
        return $this->hasOne(Answer::class,'user_task_id','id');
    }
}
