<?php

declare(strict_types=1);

namespace App\Infrastructure\Model\Cast;

use App\Enum\UserTaskStatus;
use App\Infrastructure\Model\Traits\CastsEnumTrait;
use BenSampo\Enum\Enum;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;

/**
 * Каст для полей типа SelectionRequestStatus
 */
class UserPivotTaskTypeCast implements CastsAttributes
{
    use CastsEnumTrait;

    /**
     * @var string|Enum
     */
    private string $enumClass = UserTaskStatus::class;
}
