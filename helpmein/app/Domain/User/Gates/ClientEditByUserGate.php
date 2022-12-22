<?php

declare(strict_types=1);

namespace App\Domain\User\Gates;

use App\Domain\User\Model\User;
use App\Infrastructure\Access\Gates\BaseGate;

/**
 * Гейт для редактирования пользователя
 */
class ClientEditByUserGate extends BaseGate
{
    public static function getCode(): string
    {
        return 'user-edit';
    }

    public function __invoke(User $user, string $userId): bool
    {
        // TODO: add roles
//        if ($this->isSuperAdmin($user)) {
//            return true;
//        }
        $teachers = User::query()->find($userId)->teachers()->where('user_id', $user->id)->get();
        return (bool) $teachers->count();
    }
}
