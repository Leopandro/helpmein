<?php

declare(strict_types=1);

namespace App\Infrastructure\Model\Traits;

use BenSampo\Enum\Enum;

/**
 * Каст для полей типа \BenSampo\Enum\Enum
 */
trait CastsEnumTrait
{
    /**
     * @param \Illuminate\Database\Eloquent\Model $model
     * @param string $key
     * @param mixed $value
     * @param array $attributes
     * @return TaskStatus
     * @throws \BenSampo\Enum\Exceptions\InvalidEnumMemberException
     */
    public function get($model, string $key, $value, array $attributes): ?Enum
    {
        if ($value === null) {
            return null;
        }

        if (!$this->enumClass::hasValue($value)) {
            throw new \RuntimeException(sprintf('Unable cast %s to %s enum', $value, $this->enumClass));
        }

        return new $this->enumClass($value);
    }

    public function set($model, string $key, $value, array $attributes): ?string
    {
        if ($value === null) {
            return null;
        }

        if (!$value instanceof $this->enumClass) {
            throw new \RuntimeException(
                sprintf(
                    '%s is not of type %s. Probably you should use new Enum(Enum::key) instead of Enum::key',
                    $value,
                    $this->enumClass
                )
            );
        }

        return $value->value;
    }
}
