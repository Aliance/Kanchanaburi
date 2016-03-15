<?php
namespace Aliance\Kanchanaburi\Application;

use Aliance\Kanchanaburi\Cli\CommandPosix;
use Aliance\Kanchanaburi\Cli\Option;
use Aliance\Kanchanaburi\Controller\Script\ScriptController;
use InvalidArgumentException;

declare(ticks = 1);

/**
 * Script application
 */
final class ScriptApplication extends AbstractApplication {
    /**
     * @var CommandPosix
     */
    private $Command;

    /**
     * @var string
     */
    private $scriptName = '';

    /**
     * @var string
     */
    private $scriptClassName = '';

    public function __construct() {
        pcntl_signal(SIGINT, function() { exit(); });
        pcntl_signal(SIGTERM, function() { exit(); });
        pcntl_signal(SIGUSR1, function() { exit(); });
        pcntl_signal(SIGHUP, function() { exit(); });
    }

    /**
     * @param string $scriptName
     */
    public function setScriptName($scriptName) {
        $this->scriptName = $scriptName;
        $this->scriptClassName = str_replace('.', '\\', $scriptName);
    }

    /**
     * @return string
     */
    public function getScriptName() {
        return $this->scriptName;
    }

    /**
     * Геттер наименования класса активного скрипта
     * @return string имя класса активного скрипта
     */
    public function getScriptClassName() {
        return $this->scriptClassName;
    }
    /**
     * Метод инициализации приложений
     */
    public function initialize() {
        $this->Command = new CommandPosix();

        // Определение параметра запускаемого скрипта
        $Self = $this;
        $this->Command->appendParameter(
            new Option('script', 's', 'script name', Option::TYPE_STRING, true),
            function($name, $scriptName) use ($Self) {
                $Self->setScriptName($scriptName);
            }
        );
    }
    /**
     * Метод запуска приложения
     */
    public function run() {
        $this->Command->parse(true);

        $className = $this->getScriptClassName();
        if (empty($className)) {
            return true;
        }

        if (!class_exists($className)) {
            throw new InvalidArgumentException('Script "' . $this->scriptName . '" not found');
        }

        if (!is_subclass_of($className, ScriptController::class)) {
            throw new InvalidArgumentException('"' . $this->scriptName . '" is not a script');
        }

        /** @var $Controller ScriptController */
        $Controller = new $className($this->Command);
        $Controller->setApplication($this);
        $Controller->initialize();

        return $Controller->run();
    }
}
