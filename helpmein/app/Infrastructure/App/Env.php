<?php

declare(strict_types=1);

namespace App\Infrastructure\App;

use Illuminate\Support\Facades\App;

/**
 * Сборная солянка разных проверок окружения и допустимых действий
 */
class Env
{
    public static function isProduction(): bool
    {
        return self::current() === 'production';
    }

    public static function isRelease(): bool
    {
        return self::current() === 'release';
    }

    public static function isDev(): bool
    {
        return self::current() === 'dev';
    }

    public static function isLocal(): bool
    {
        return self::current() === 'local';
    }

    public static function isTesting(): bool
    {
        return self::current() === 'testing';
    }

    public static function current(): string
    {
        return App::environment();
    }

    /**
     * Разрешен ли доступ к horizon без авторизации
     * @return bool
     */
    public static function horizonWithoutAuthorizationAllowed(): bool
    {
        return !self::isProduction();
    }

    /**
     * Разрешена ли отправка логов в sentry
     * @return bool
     */
    public static function sendLogsToSentryAllowed(): bool
    {
        return self::isProduction() || self::isRelease();
    }

    /**
     * Разрешены ли алерты в телеграм
     * @return bool
     */
    public static function sendAlertsToTelegramAllowed(): bool
    {
        return self::isProduction();
    }

    /**
     * Разрешена ли отправка смс (иначе просто запись в лог)
     * @return bool
     */
    public static function smsSendAllowed(): bool
    {
        return self::isProduction();
    }
}
