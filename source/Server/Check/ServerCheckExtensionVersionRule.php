<?php
namespace Aliance\Kanchanaburi\Server\Check;

use Aliance\Kanchanaburi\Server\Check\Exception\ServerCheckRuleExecuteCommandException;
use Aliance\Kanchanaburi\Server\Check\Exception\ServerCheckRuleFailException;

/**
 * Server check extension version rule
 */
class ServerCheckExtensionVersionRule extends AbstractServerCheckRule {
    /**
     * @var string
     */
    protected $versionCommand = '';

    /**
     * @throws ServerCheckRuleExecuteCommandException
     * @throws ServerCheckRuleFailException
     */
    public function check() {
        $command = $this->getSecureConnectCommand() . ' -- ' . $this->versionCommand;
        $commandResult = exec($command);

        if ($commandResult === false) {
            throw new ServerCheckRuleExecuteCommandException();
        }

        if (version_compare($commandResult, $this->getValue()) < 0) {
            throw new ServerCheckRuleFailException($commandResult);
        }
    }
}
