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
            'name' => $user->teachers->first()->name,
            'surname' => $user->teachers->first()->surname,
            'email' => $user->email,
        ];
    }
}
