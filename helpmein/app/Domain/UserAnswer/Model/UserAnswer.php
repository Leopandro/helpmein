<?php
namespace App\Domain\UserAnswer\Model;

use App\Enum\UserTaskStatus;
use Illuminate\Database\Eloquent\Model;

/**
 * @property string status
 * @property string answer
 */
class UserAnswer extends Model
{
    protected $table = 'user_answer';

    protected $casts = [
        'status' => UserTaskStatus::class
    ];

    protected $primaryKey = 'id';
}
