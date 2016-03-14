<?php
namespace Aliance\Kanchanaburi\Logger;

use Monolog\Logger;

/**
 * Monolog wrapper
 */
class LoggerFactory {
    /**
     * @var Logger[] созданные объекты логгеров
     */
    private static $Loggers = [];

    /**
     * @var GlobalContext экземпляр глобального контекста
     */
    private static $GlobalContext = null;

    /**
     * @return Logger экземпляр базового логгера
     */
    public static function getRootLogger() {
        return static::getLogger('Root');
    }

    /**
     * Получаем объект логгера по типу
     * Если еще не был создан, то создаем новый объект
     * @param string $type тип логгера, по-умолчанию Root
     * @return Logger экземпляр логгера
     */
    public static function getLogger($type) {
        if (!isset(self::$Loggers[$type])) {
            self::$Loggers[$type] = self::createLogger($type);
        }
        return self::$Loggers[$type];
    }

    /**
     * Создаем объект логгера нужного типа
     * @param string $type тип логгера
     * @return Logger экземпляр логгера
     */
    private static function createLogger($type) {
        $Logger = new Logger($type);

        $loggers = Config::getInstance()->get('logger');

        $loggerConfig = $loggers[$type];

        $Handler = static::createHandler($type, $loggerConfig['level']);
        $Handler->setFormatter(static::createLogFormatter());
        $Logger->pushHandler($Handler);

        $Logger->pushProcessor(new GlobalContextProcessor());

        return $Logger;
    }

    /**
     * Создаем хэндлер по типу и конфигу
     * @param string $logName
     * @param int $level
     * @return AbstractHandler
     */
    protected static function createHandler($logName, $level) {
        return new StreamHandler(PATH_LOG . $logName . '.log', $level);
    }

    /**
     * Получаем объект форматирования записей логов для файлов
     * @return FormatterInterface экземпляр форматера
     */
    protected static function createLogFormatter() {
        return new LogFormatter("%datetime% %level_name% %context%: %message%\n", 'Y-m-d H:i:s', true);
    }

    /**
     * Получаем объект работы с глобальным контекстом
     * @return GlobalContext экземпляр глобального контекста
     */
    public static function getGlobalContext() {
        if (!isset(self::$GlobalContext)) {
            self::$GlobalContext = new GlobalContext();
        }
        return self::$GlobalContext;
    }
}
