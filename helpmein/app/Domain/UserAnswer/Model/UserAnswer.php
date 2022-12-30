<?php
namespace App\Domain\UserAnswer\Model;

use App\Enum\UserTaskStatus;
use Illuminate\Database\Eloquent\Model;

class UserAnswer extends Model
{
    protected $table = 'user_answer';

    protected $casts = [
        'status' => UserTaskStatus::class
    ];
}
