<?php

namespace App\Http\Controllers;

use App\Domain\Task\Model\Task;
use App\Domain\Task\Resource\TaskInfoResource;
use App\Domain\User\Model\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;

class TestController extends Controller
{
    public function list(Request $request) {
        $tasks = QueryBuilder::for(Task::class)
            ->allowedFilters(['name', 'description'])
            ->get();
        return TaskInfoResource::collection($tasks)->toArray($request);
    }
}
