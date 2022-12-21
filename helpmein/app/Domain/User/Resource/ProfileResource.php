<?php

declare(strict_types=1);

namespace App\Domain\User\Resource;

use App\Domain\Client\Model\Client;
use App\Infrastructure\Http\Resource\JsonResource;

class ProfileResource extends JsonResource
{
    public function toArray($request): array
    {
        /** @var Client $user */
        $user = $this->resource;
        return [
            'id' => $user->id,
            'name' => $user->name,
            'surname' => $user->surname,
            'email' => $user->email,
        ];
    }
}
