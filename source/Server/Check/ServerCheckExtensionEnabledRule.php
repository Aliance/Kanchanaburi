<?php
namespace Aliance\Kanchanaburi\Server\Check;

use Aliance\Kanchanaburi\Server\Check\Exception\ServerCheckRuleExecuteCommandException;
use Aliance\Kanchanaburi\Server\Check\Exception\ServerCheckRuleFailException;

/**
 *
 */
class ServerCheckExtensionEnabledRule extends AbstractServerCheckRule {
    /**
     * @var string
     */
    const STATE_ENABLED = 'enabled';

    /**
     * @var string
     */
    const STATE_DISABLED = 'disabled';

    /**
     * @var array
     */
    private static $statesTrue = [
        self::STATE_ENABLED => self::STATE_ENABLED,
    ];

    /**
     * @var array
     */
    private static $statesFalse = [
        self::STATE_DISABLED => self::STATE_DISABLED,
    ];

    /**
     * @var string
     */
    protected $enabledCommand = '';

    /**
     * {@inheritdoc}
     * @throws ServerCheckRuleExecuteCommandException
     * @throws ServerCheckRuleFailException
     */
    public function check() {
        $command = $this->getSecureConnectCommand() . ' -- ' . $this->enabledCommand;
        $commandResult = strtolower(exec($command));
        if ($commandResult === false) {
            throw new ServerCheckRuleExecuteCommandException();
        }

        if ($this->getValue() === true && array_key_exists($commandResult, self::$statesTrue)) {
            return true;
        }

        if ($this->getValue() === false && array_key_exists($commandResult, self::$statesFalse)) {
            return true;
        }

        throw new ServerCheckRuleFailException($commandResult);
    }
}
