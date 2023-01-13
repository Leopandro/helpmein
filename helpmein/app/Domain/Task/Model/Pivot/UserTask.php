<?php
namespace App\Domain\Task\Model\Pivot;

use App\Domain\UserAnswer\Model\Answer;
use App\Enum\UserTaskStatus;
use Illuminate\Database\Eloquent\Relations\Pivot;

class UserTask extends Pivot
{
    protected $table = 'user_task';

    protected $primaryKey = 'id';
    public $timestamps = true;
    protected $casts = [
        'status' => UserTaskStatus::class
    ];

    public function answer()
    {
        return $this->belongsTo(Answer::class, 'answer_id', 'id');
    }
}
