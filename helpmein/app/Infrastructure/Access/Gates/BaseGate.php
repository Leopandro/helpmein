<?php

declare(strict_types=1);

namespace App\Infrastructure\Access\Gates;

use App\Domain\User\Model\User;
use App\Infrastructure\Model\Permissions\Role;

/**
 * Базовый класс для всех гейтов
 */
abstract class BaseGate
{
    /**
     * Получает код гейта для метода Gate::define
     * например edit-settings, create-user
     * @return string
     */
    abstract public static function getCode(): string;

    /**
     * Используется для разрешения тестировать защищенные маршруты
     * @param User $user
     * @return bool
     */
    public function isSuperAdmin(User $user): bool
    {
        if ($user->email === 'admin_0@example.net' || $user->hasRole(Role::ROLE_ADMIN)) {
            return true;
        }

        return false;
    }
}
