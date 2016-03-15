<?php
namespace Aliance\Kanchanaburi\Server\Check;

use Aliance\Kanchanaburi\Server\Check\Exception\ServerCheckEmptyExtensionException;
use Aliance\Kanchanaburi\Server\Check\Exception\ServerCheckRuleExecuteCommandException;
use Aliance\Kanchanaburi\Server\Check\Exception\ServerCheckRuleFailException;

/**
 * Abstract check extension rule
 */
abstract class ServerCheckExtensionRule extends AbstractServerCheckRule {
    /**
     * @var string
     */
    protected $extensionName = '';

    /**
     * @param string $extensionName
     */
    public function setExtensionName($extensionName) {
        $this->extensionName = $extensionName;
    }

    /**
     * @return string
     */
    public function getExtensionName() {
        return $this->extensionName;
    }

    /**
     * {@inheritdoc}
     * @throws ServerCheckRuleExecuteCommandException
     * @throws ServerCheckRuleFailException
     * @throws ServerCheckEmptyExtensionException
     */
    public function check() {
        if (empty($this->extensionName)) {
            throw new ServerCheckEmptyExtensionException();
        }

        $command = $this->getSecureConnectCommand()
            . ' -- ' . '\'php -r "echo (int)extension_loaded(\"' . $this->getExtensionName() . '\");"\'';
        $commandResult = exec($command);
        if ($commandResult === false) {
            throw new ServerCheckRuleExecuteCommandException();
        }

        $result = (bool) $commandResult;
        if ($result !== $this->getValue()) {
            throw new ServerCheckRuleFailException($this->getExtensionName());
        }

        return true;
    }
}
