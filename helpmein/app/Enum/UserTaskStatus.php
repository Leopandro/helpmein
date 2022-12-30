<?php

declare(strict_types=1);

namespace App\Enum;

use BenSampo\Enum\Enum;

/**
 *
 */
class UserTaskStatus extends Enum
{
    public const NOT_ASSIGNED = 'not_assigned';

    public const ASSIGNED = 'assigned';

    public const IN_REVIEW = 'in_review';

    public const REASSIGNED = 'reassigned';

    public const FINISHED = 'finished';
    /**
     * Возвращает значения в порядке возрастания
     * Нужно для сортировки в таблицах
     * @return string[]
     */
    public static function getValuesAsc(): array
    {
        return [
            self::NOT_ASSIGNED,
            self::ASSIGNED,
            self::REASSIGNED,
            self::FINISHED,
            self::IN_REVIEW,
        ];
    }
}
