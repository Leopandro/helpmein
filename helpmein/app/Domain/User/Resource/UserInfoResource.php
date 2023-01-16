<?php

declare(strict_types=1);

namespace App\Domain\User\Resource;

use App\Domain\Client\Model\Client;
use App\Domain\User\Model\User;
use App\Infrastructure\Http\Resource\JsonResource;
use Carbon\Carbon;

class UserInfoResource extends JsonResource
{
    public function toArray($request): array
    {
        /** @var Client $user */
        $user = $this->resource;
        $assigned = $user->answers()->where('status','=','assigned')->count();
        $reassigned = $user->answers()->where('status','=','reassigned')->count();
        $in_review = $user->answers()->where('status','=','in_review')->count();
        $finished = $user->answers()
            ->where('status','=','finished')
            ->whereDate('updated_at', '>', Carbon::now()->subDays(3))
            ->count();
        $finished10 = $user->answers()
            ->where('status','=','finished')
            ->whereDate('updated_at', '>', Carbon::now()->subDays(10))
            ->count();
        return [
            'id' => $user->id,
            'name' => $user->teachers->first()->pivot->name,
            'active' => $user->teachers->first()->pivot->active,
            'surname' => $user->teachers->first()->pivot->surname,
            'taskStats' => [
                'active' => $assigned + $reassigned,
                'in_review' => $in_review,
                'finished' => $finished,
                'finished10' => $finished10,
            ],
            'email' => $user->email,
        ];
    }
}
