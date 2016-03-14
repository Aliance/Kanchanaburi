<?php
namespace Aliance\Kanchanaburi\Error;

use Aliance\Kanchanaburi\Error\Exception\GCCacheEntryException;
use Aliance\Kanchanaburi\Error\Exception\PhpErrorException;
use Aliance\Kanchanaburi\Error\Exception\PhpException;
use Aliance\Kanchanaburi\Error\Exception\PhpFatalException;
use Aliance\Kanchanaburi\Error\Exception\PhpNoticeException;
use Aliance\Kanchanaburi\Error\Exception\PhpWarningException;
use Aliance\Kanchanaburi\Error\Exception\UndefinedOffsetException;
use Aliance\Kanchanaburi\Error\Exception\UndefinedPropertyException;
use Aliance\Kanchanaburi\Error\Exception\UndefinedVariableException;
use Closure;
use Exception;

require_once('Exception/PhpException.php');
require_once('Exception/PhpErrorException.php');
require_once('Exception/PhpWarningException.php');
require_once('Exception/PhpNoticeException.php');
require_once('Exception/GCCacheEntryException.php');
require_once('Exception/UndefinedOffsetException.php');
require_once('Exception/UndefinedPropertyException.php');
require_once('Exception/UndefinedVariableException.php');

/**
 * Error Handler class
 * Wraps PHP errors into exceptions
 */
final class ErrorHandler {
    /**
     * @var string
     */
    const MESSAGE_UNDEFINED_VARIABLE = 'Undefined variable: ';

    /**
     * @var string
     */
    const MESSAGE_UNDEFINED_OFFSET = 'Undefined offset: ';

    /**
     * @var string
     */
    const MESSAGE_UNDEFINED_INDEX = 'Undefined index: ';

    /**
     * @var string
     */
    const MESSAGE_UNDEFINED_PROPERTY = 'Undefined property: ';

    /**
     * @var string
     */
    const MESSAGE_GC_CACHE_ENTRY = 'GC cache entry ';

    /**
     * @var ErrorHandler
     */
    private static $Instance;

    /**
     * @var array
     */
    private $errorNumberMap = [];

    /**
     * @var array
     */
    private $errorNoticeMap = [];

    /**
     * @var array
     */
    private $errorWarningMap = [];

    /**
     * @var Exception
     */
    private $LastException;

    /**
     * @var Closure
     */
    private $FatalErrorHandler;

    /**
     * @return ErrorHandler
     */
    public static function getInstance() {
        return self::$Instance ?: self::$Instance = new self();
    }

    private function __construct() {
        $this->errorNumberMap = [
            E_USER_ERROR        => PhpErrorException::class,
            E_RECOVERABLE_ERROR => PhpErrorException::class,

            E_USER_WARNING    => PhpWarningException::class,
            E_WARNING         => PhpWarningException::class,
            E_CORE_WARNING    => PhpWarningException::class,
            E_COMPILE_WARNING => PhpWarningException::class,

            E_USER_NOTICE => PhpNoticeException::class,
            E_NOTICE      => PhpNoticeException::class,
            E_STRICT      => PhpNoticeException::class,
        ];

        $this->errorNoticeMap = [
            self::MESSAGE_UNDEFINED_INDEX    => UndefinedOffsetException::class,
            self::MESSAGE_UNDEFINED_OFFSET   => UndefinedOffsetException::class,
            self::MESSAGE_UNDEFINED_PROPERTY => UndefinedPropertyException::class,
            self::MESSAGE_UNDEFINED_VARIABLE => UndefinedVariableException::class,
        ];

        $this->errorWarningMap = [
            self::MESSAGE_GC_CACHE_ENTRY => GCCacheEntryException::class,
        ];
    }

    /**
     * @param int $errno
     * @param string $errstr
     * @param string $errfile
     * @param int $errline
     * @param array $errcontext
     * @throws Exception
     */
    public function handleError($errno, $errstr, $errfile, $errline, $errcontext = []) {
        if (!error_reporting()) {
            return;
        }

        $exceptionClass = '';

        switch ($errno) {
            case E_NOTICE:
                foreach ($this->errorNoticeMap as $message => $class) {
                    if (strpos($errstr, $message) !== false) {
                        $exceptionClass = $class;
                        break;
                    }
                }
                break;
            case E_WARNING:
                foreach ($this->errorWarningMap as $message => $class) {
                    if (strpos($errstr, $message) !== false) {
                        $exceptionClass = $class;
                        break;
                    }
                }
                break;
            case E_ERROR:
                $exceptionClass = PhpFatalException::class;
                break;
        }

        if (empty($exceptionClass)) {
            $exceptionClass = array_key_exists($errno, $this->errorNumberMap)
                ? $this->errorNumberMap[$errno]
                : PhpException::class;
        }

        $this->LastException = new $exceptionClass(sprintf(
            "code: %s\nmessage: %s\nfile: %s\nline: %s\n",
            $errno,
            $errstr,
            $errfile,
            $errline
        ));

        throw $this->LastException;
    }

    /**
     * Метод обработки ситуации завершения работы текущего процесса
     */
    public function handleShutdown() {
        try {
            $error = error_get_last();
            if ($error !== NULL) {
                if ($error['type'] == E_ERROR) {
                    $this->handleError(E_ERROR, $error['message'], $error['file'], $error['line']);
                }
            }
        } catch (PhpFatalException $Ex) {
            $this->handlePhpFatalException($Ex);
        }
    }

    /**
     * Метод вызова обработчика фаталов PHP
     * @param Exception $Ex исходное фатальное исключение
     * @throws Exception исходное фатальное исключение, если обработчик не был определен
     */
    public function handlePhpFatalException(Exception $Ex) {
        if ($this->FatalErrorHandler !== NULL) {
            $Handler = $this->FatalErrorHandler;
            $Handler($Ex);
        } else {
            throw $Ex;
        }
    }

    /**
     * Метод регистрации обработчика ошибочных ситуаций в системе
     */
    public function register() {
        set_error_handler([$this, 'handleError'], error_reporting());
        register_shutdown_function([$this, 'handleShutdown']);
    }

    /**
     * Сеттер внешнего обработчика фаталов на PHP
     * @param Closure $Handler
     */
    public function setPhpFatalExceptionHandler(Closure $Handler) {
        $this->FatalErrorHandler = $Handler->bindTo($this, get_called_class());
    }
}
