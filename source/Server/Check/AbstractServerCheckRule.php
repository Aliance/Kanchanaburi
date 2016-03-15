<?php
namespace Aliance\Kanchanaburi\Server\Check;

use Aliance\Kanchanaburi\Server\Server;

/**
 * Abstract server check rule
 */
abstract class AbstractServerCheckRule {
    /**
     * @var string
     */
    private $value;

    /**
     * @var Server
     */
    private $Server;

    /**
     * @param mixed $value
     * @param Server $Server
     */
    public function __construct($value, Server $Server) {
        $this->value = $value;
        $this->Server = $Server;
    }

    /**
     * @return mixed
     */
    protected function getValue() {
        return $this->value;
    }

    /**
     * @return Server
     */
    protected function getServer() {
        return $this->Server;
    }

    /**
     * @return string
     */
    protected function getSecureConnectCommand() {
        return $this->getServer()->getSecureConnectionString();
    }

    /**
     * @return bool
     */
    abstract public function check();
}
