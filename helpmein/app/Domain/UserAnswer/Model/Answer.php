<?php
namespace App\Domain\UserAnswer\Model;

use App\Enum\UserTaskStatus;
use Illuminate\Database\Eloquent\Model;

/**
 * @property integer id
 * @property string status
 * @property string answer
 */
class Answer extends Model
{
    protected $table = 'answer';

    protected $casts = [
        'status' => UserTaskStatus::class
    ];

    protected $guarded = [];

    protected $primaryKey = 'id';
}
