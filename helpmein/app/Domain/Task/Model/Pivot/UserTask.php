<?php
namespace App\Domain\Task\Model\Pivot;

use App\Domain\UserAnswer\Model\UserAnswer;
use App\Enum\UserTaskStatus;
use Illuminate\Database\Eloquent\Relations\Pivot;

class UserTask extends Pivot
{
    protected $table = 'user_task';

    protected $primaryKey = 'id';

    protected $casts = [
        'status' => UserTaskStatus::class
    ];

    public function answer()
    {
        return $this->belongsTo(UserAnswer::class, 'answer_id', 'id');
    }
}
