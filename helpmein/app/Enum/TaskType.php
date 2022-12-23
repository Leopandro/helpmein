<?php

declare(strict_types=1);

namespace App\Enum;

use BenSampo\Enum\Enum;

/**
 *
 * @method static static DRAFT()
 */
class TaskType extends Enum
{
    public const ESSAY = 'essay';

    public const TASK = 'task';
    /**
     * Возвращает значения в порядке возрастания
     * Нужно для сортировки в таблицах
     * @return string[]
     */
    public static function getValuesAsc(): array
    {
        return [
            self::ESSAY,
            self::TASK,
        ];
    }
}
