<?php

declare(strict_types=1);

namespace App\Infrastructure\Model\Cast;

use App\Enum\TaskType;
use App\Infrastructure\Model\Traits\CastsEnumTrait;
use BenSampo\Enum\Enum;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;

/**
 * Каст для полей типа SelectionRequestStatus
 */
class TaskTypeCast implements CastsAttributes
{
    use CastsEnumTrait;

    /**
     * @var string|Enum
     */
    private string $enumClass = TaskType::class;
}
