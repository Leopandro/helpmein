<?php
namespace App\Domain\Task\Model\Trait;

use App\Domain\Task\Model\Task;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

trait TeacherClientTaskLoaderTrait {
    /** Все задачи с назначениями и без */
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

    /** Все задачи только с назначениями */
    public static function buildQueryForTeacherClientTaskList(Request $request): LengthAwarePaginator
    {
        return QueryBuilder::for(Task::class)
            ->with('answer')
            ->allowedFilters([
                AllowedFilter::callback('task_category_id', static function (Builder $query, $value) {
                    return $query->where('task_category_id', '=',$value);
                }),
                AllowedFilter::callback('user_id', static function (Builder $query, $value) {
                    return $query
                        ->with('clients')
                        ->whereHas(
                            'clients', function($query) use ($value) {
                            $query->where('user_task.user_id', '=', $value);
                        });
                }),
                AllowedFilter::callback('assigned', fn(Builder $query, $value) =>
                    $query
                        ->with('answer')
                        ->whereHas('answer', fn($query) =>
                            $query->whereIn('answer.status', ['assigned', 'reassigned'])
                        )
                ),
                AllowedFilter::callback('in_review', fn(Builder $query, $value) =>
                    $query->with('answer')
                        ->whereHas('answer', fn($query) =>
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
