<?php
namespace Aliance\Kanchanaburi\Application;

use Aliance\Kanchanaburi\Application\Exception\UndefinedApplicationException;
use Aliance\Kanchanaburi\Application\Exception\UnknownApplicationException;
use Aliance\Kanchanaburi\Error\ErrorHandler;
use Aliance\Kanchanaburi\Traits\SingletonTrait;

/**
 * Application instance factory
 */
final class ApplicationFactory {
    use SingletonTrait;

    /**
     * @var string
     */
    const MODE_WEB = 'MODE_WEB';

    /**
     * Script application via CLI SAPI
     * @var int
     */
    const TYPE_SCRIPT = 0;

    /**
     * Network application via FCGI SAPI
     * @var int
     */
    const TYPE_WEB = 1;

    /**
     * @var string
     */
    private static $charset = 'UTF-8';

    /**
     * @var string
     */
    private static $locale = 'ru_RU.UTF-8';

    private function __construct() {
        mb_internal_encoding(self::$charset);
        ini_set('intl.default_locale', self::$locale);
        ErrorHandler::getInstance()->register();
    }

    /**
     * @param string $charset
     */
    public static function setCharset($charset) {
        self::$charset = (string) $charset;
    }

    /**
     * @param string $locale
     */
    public static function setLocale($locale) {
        self::$locale = (string) $locale;
    }

    /**
     * Creates an application
     * @param int|null $forceType application type (self::TYPE_*). Autodetect by default
     * @return AbstractApplication
     * @throws UndefinedApplicationException
     */
    public function createApplication($forceType = null) {
        $type = $forceType ?: $this->getType();

        switch ($type) {
            case self::TYPE_SCRIPT:
                return new ScriptApplication();
            case self::TYPE_WEB:
                return new WebApplication();
            default:
                throw new UndefinedApplicationException($type);
        }
    }

    /**
     * Autodetect application by PHP_SAPI
     * @return int application type (self::TYPE_*)
     * @throws UnknownApplicationException
     */
    protected function getType() {
        switch (PHP_SAPI) {
            case 'cli':
                // TODO: differ console and cron by TERM environment
                return self::TYPE_SCRIPT;
            case 'fpm-fcgi':
                return self::TYPE_WEB;
            default:
                throw new UnknownApplicationException(PHP_SAPI);
        }
    }
}
