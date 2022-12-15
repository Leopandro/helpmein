<?php
namespace App\Domain\TaskCategory\Model;

use Illuminate\Database\Eloquent\Model;
use Kalnoy\Nestedset\NodeTrait;

/**
 * @property int id
 */
class TaskCategory extends Model
{
    use NodeTrait;
    protected $table = 'task_category';

    public $timestamps = false;

    protected $guarded = [];
}
