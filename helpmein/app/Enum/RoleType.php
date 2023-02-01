<?php

declare(strict_types=1);

namespace App\Enum;

use BenSampo\Enum\Enum;

/**
 *
 */
class RoleType extends Enum
{
    public const TEACHER = 'Teacher';

    public const CLIENT = 'Client';
    /**
     * Возвращает значения в порядке возрастания
     * Нужно для сортировки в таблицах
     * @return string[]
     */
    public static function getValuesAsc(): array
    {
        return [
            self::TEACHER,
            self::CLIENT,
        ];
    }
}
