<?php
namespace App\Domain\Task\Model\Pivot;

use App\Domain\Client\Model\Client;
use App\Domain\UserAnswer\Model\Answer;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
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

    public function client(): BelongsTo
    {
        return $this->BelongsTo(Client::class,'user_id','id');
    }
}
