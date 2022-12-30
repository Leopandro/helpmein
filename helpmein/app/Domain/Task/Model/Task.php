<?php
namespace App\Domain\Task\Model;

use App\Domain\Task\Model\Pivot\UserTask;
use App\Domain\Task\Model\Trait\TeacherClientTaskLoaderTrait;
use App\Domain\TaskCategory\Model\TaskCategory;
use App\Domain\User\Model\User;
use App\Infrastructure\Model\Cast\TaskTypeCast;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * class Task
 *
 * @property integer id
 * @property string name
 * @property string description
 * @property string comment
 * @property string comment_client
 * @property array questions
 * @property string difficult_level
 * @property string type
 * @property integer task_category_id
 * @property integer user_id
 * @property string created_at
 * @property string updated_at
 */
class Task extends Model
{
    use TeacherClientTaskLoaderTrait;
    protected $table = 'task';

    protected $guarded = [];

    protected $casts = [
        'type' => TaskTypeCast::class,
    ];

    protected function questions(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => json_decode($value, true),
            set: fn ($value) => json_encode($value),
        );
    }

    public function clients(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_task', 'task_id', 'user_id')->using(UserTask::class)->withPivot([
            'answer_id',
        ]);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id','user_id');
    }

    public function folder(): BelongsTo
    {
        return $this->belongsTo(TaskCategory::class, 'id','task_category_id');
    }
}
