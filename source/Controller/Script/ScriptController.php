<?php
namespace Aliance\Kanchanaburi\Controller\Script;

use Aliance\Kanchanaburi\Cli\CommandPosix;
use Aliance\Kanchanaburi\Cli\Option;

/**
 * Abstract script controller
 */
abstract class ScriptController extends AbstractController {
    /**
     * Префикс сеттеров значений параметров
     */
    const PREFIX_SETTER = 'set';

    /**
     * @var Option[]
     */
    protected $options = [];

    /**
     * @var CommandPosix
     */
    private $CommandPosix = null;

    /**
     * @return CommandPosix
     */
    protected function getCommandPosix() {
        return $this->CommandPosix;
    }

    /**
     * @param string $name
     * @param string $value
     */
    private function acceptParameter($name, $value) {
        $methodName = self::PREFIX_SETTER . ucfirst($name);
        if (method_exists($this, $methodName)) {
            $this->{$methodName}($value);
        }
    }

    /**
     * @param CommandPosix $Command
     */
    public function __construct($Command = null) {
        if ($Command !== NULL && ($Command instanceof CommandPosix)) {
            $this->CommandPosix = $Command;
        }
    }

    /**
     * {@inheritdoc}
     * @return ScriptController
     */
    public function initialize() {
        $this->getCommandPosix()->appendHelpParameter('display script help usages');

        $Self = $this;
        foreach ($this->options as $Option) {
            $this->getCommandPosix()->appendParameter($Option, function($name, $value) use ($Self) {
                $Self->acceptParameter($name, $value);
            });
        }
        $this->getCommandPosix()->parse(true);

        return $this;
    }
}
