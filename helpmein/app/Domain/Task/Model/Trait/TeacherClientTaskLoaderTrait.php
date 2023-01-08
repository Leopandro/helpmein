<?php
namespace App\Domain\Task\Model\Trait;

use App\Domain\Task\Model\Task;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

trait TeacherClientTaskLoaderTrait {
    public static function buildQueryForTeacherClientTaskAllList(Request $request): LengthAwarePaginator
    {
        return QueryBuilder::for(Task::class)
            ->allowedFilters([
                AllowedFilter::callback('task_category_id', static function (Builder $query, $value) {
                    return $query->where('task_category_id', '=',$value);
                }),
                AllowedFilter::callback('user_id', static function (Builder $query, $value) {
                    return $query
                        ->with(['clients' => function($query) use ($value) {
                            $query->where('user_task.user_id', '=', $value);
                        }]);
                }),
                AllowedFilter::callback('difficult_level', static function (Builder $query, $value) {
                    return $query->where('difficult_level', '=',$value);
                }),
            ])
            ->orderBy('id')
            ->paginate($request->get('count'));
    }

    public static function buildQueryForTeacherClientTaskList(Request $request): LengthAwarePaginator
    {
        return QueryBuilder::for(Task::class)
            ->with('answers')
            ->allowedFilters([
                AllowedFilter::callback('task_category_id', static function (Builder $query, $value) {
                    return $query->where('task_category_id', '=',$value);
                }),
                AllowedFilter::callback('user_id', static function (Builder $query, $value) {
                    return $query
                        ->with('answers')
                        ->whereHas(
                            'answers', function($query) use ($value) {
                                $query->where('user_task.user_id', '=', $value);
                            });
                }),
                AllowedFilter::callback('assigned', fn(Builder $query, $value) =>
                    $query
                        ->with('answers' )
                        ->whereHas('answers', fn($query) =>
                            $query->whereIn('answer.status', ['assigned', 'reassigned'])
                        )
                ),
                AllowedFilter::callback('in_review', fn(Builder $query, $value) =>
                    $query->with('answers')
                        ->whereHas('answers', fn($query) =>
                            $query->where('answer.status', '=', 'in_review')
                        )
                ),
                AllowedFilter::callback('all', fn(Builder $query, $value) =>
                    $query
                ),
                AllowedFilter::callback('difficult_level', static function (Builder $query, $value) {
                    return $query->where('difficult_level', '=',$value);
                }),
            ])
            ->orderBy('id')
            ->paginate($request->get('count'));
    }
}
