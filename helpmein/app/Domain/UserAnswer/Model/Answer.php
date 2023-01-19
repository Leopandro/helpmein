<?php
namespace App\Domain\UserAnswer\Model;

use App\Domain\Task\Model\Pivot\UserTask;
use App\Enum\UserTaskStatus;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property integer id
 * @property string status
 * @property string answer
 * @property string questions
 * @property string teacher_comment
 * @property integer mistakes
 */
class Answer extends Model
{
    protected $table = 'answer';

    protected $casts = [
        'status' => UserTaskStatus::class
    ];

    protected function questions(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => json_decode($value, true),
            set: fn ($value) => json_encode($value),
        );
    }

    public function userTask(): BelongsTo
    {
        return $this->belongsTo(UserTask::class);
    }

    protected $guarded = [];

    protected $primaryKey = 'id';
}
