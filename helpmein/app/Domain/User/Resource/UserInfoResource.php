<?php

declare(strict_types=1);

namespace App\Domain\User\Resource;

use App\Domain\User\Model\User;
use App\Infrastructure\Http\Resource\JsonResource;

class UserInfoResource extends JsonResource
{
    public function toArray($request): array
    {
        /** @var User $user */
        $user = $this->resource;

        return [
            'first_name' => $user->name,
            'last_name' => $user->surname,
            'email' => $user->email,
        ];
    }
}
