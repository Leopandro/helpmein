<?php

namespace App\Http\Controllers;

use App\Domain\TaskCategory\Model\TaskCategory;
use App\Domain\User\Model\User;
use Illuminate\Http\Request;

class CategoryTreeController extends Controller
{
    public function list(Request $request) {
        /** @var User $user */
        $user = auth('sanctum')->user();
        /** @var TaskCategory $categoryParent */
        $categoryParent = TaskCategory::query()
            ->whereNull('parent_id')
            ->where('user_id','=',$user->id)
            ->firstOrCreate([], [
                'name' => 'Эту папку не должно быть видно',
                'user_id' => auth('sanctum')->id()
            ]);
        $tree = TaskCategory::defaultOrder('asc')->descendantsAndSelf($categoryParent->id)->toTree()->first();;
        return $tree->toArray();
    }

    public function add(Request $request) {
        /** @var User $user */
        $user = auth('sanctum')->user();
        /** @var TaskCategory $categoryParent */
        $categoryParent = TaskCategory::query()
            ->where('id','=',$request->get('id'))
            ->firstOrFail();
        $children = new TaskCategory([
            'name' => 'Новая папка',
            'user_id' => $user->id
        ]);
        $categoryParent->prependNode($children);
        return $children->toArray();
    }

    public function edit(Request $request) {
        /** @var User $user */
        $user = auth('sanctum')->user();
        /** @var TaskCategory $categoryParent */
        $categoryParent = TaskCategory::query()
            ->where('id','=',$request->get('id'))
            ->firstOrFail();
        $categoryParent->update([
            'name' => $request->get('name')
        ]);
        return $categoryParent->toArray();
    }

    public function replace(Request $request) {
        /** @var User $user */
        $user = auth('sanctum')->user();
        /** @var TaskCategory $categoryParent */
        $categoryFrom = TaskCategory::query()
            ->where('id','=',$request->get('from_id'))
            ->firstOrFail();
        $categoryTo = TaskCategory::query()
            ->where('id','=',$request->get('to_id'))
            ->firstOrFail();
        if ($request->get('move_to') == 'after') {
            $categoryFrom->afterNode($categoryTo)->save();
        } else {
            $categoryFrom->beforeNode($categoryTo)->save();
        }
        return $this->getSuccessResponse([]);
    }

    public function delete(Request $request) {
        /** @var User $user */
        $user = auth('sanctum')->user();
        /** @var TaskCategory $categoryParent */
        $categoryParent = TaskCategory::query()
            ->where('id','=',$request->get('id'))
            ->firstOrFail()
            ->delete();
        return $this->getSuccessResponse([]);
    }
}
