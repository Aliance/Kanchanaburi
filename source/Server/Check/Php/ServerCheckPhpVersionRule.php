<?php
namespace Aliance\Kanchanaburi\Server\Check;

use Aliance\Kanchanaburi\Server\Check\Exception\ServerCheckRuleExecuteCommandException;
use Aliance\Kanchanaburi\Server\Check\Exception\ServerCheckRuleFailException;

/**
 * Check PHP version rule
 */
final class ServerCheckPhpVersionRule extends AbstractServerCheckRule {
    /**
     * {@inheritdoc}
     * @throws ServerCheckRuleExecuteCommandException
     * @throws ServerCheckRuleFailException
     */
    public function check() {
        $command = $this->getSecureConnectCommand()
            . ' -- ' . '\'php -r "echo version_compare(phpversion(), \'' . $this->getValue() . '\');"\'';
        $commandResult = exec($command);
        if ($commandResult === false) {
            throw new ServerCheckRuleExecuteCommandException();
        }

        $result = (int) $commandResult;
        if ($result < 0) {
            throw new ServerCheckRuleFailException();
        }

        return true;
    }
}
