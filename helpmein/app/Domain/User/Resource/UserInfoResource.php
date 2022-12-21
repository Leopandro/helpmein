<?php

declare(strict_types=1);

namespace App\Domain\User\Resource;

use App\Domain\Client\Model\Client;
use App\Domain\User\Model\User;
use App\Infrastructure\Http\Resource\JsonResource;

class UserInfoResource extends JsonResource
{
    public function toArray($request): array
    {
        /** @var Client $user */
        $user = $this->resource;
        return [
            'id' => $user->id,
            'name' => $user->teachers->first()->pivot->name,
            'active' => $user->teachers->first()->pivot->active,
            'surname' => $user->teachers->first()->pivot->surname,
            'email' => $user->email,
        ];
    }
}
