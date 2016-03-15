<?php
namespace Aliance\Kanchanaburi\Server;

/**
 * Nginx server
 */
final class NginxServer {
    /**
     * @var int
     */
    private $role = Server::ROLE_NONE;

    /**
     * @var string
     */
    private $listen = '';

    /**
     * @var string[]
     */
    private $serverNames = [];

    /**
     * @var string
     */
    private $socket = '';

    /**
     * @param int $role
     * @param array $config
     */
    public function __construct($role, array $config) {
        $this->role = $role;
        $this->listen = $config['listen'];
        $this->serverNames = $config['server_names'];
        if (array_key_exists('socket', $config)) {
            $this->socket = $config['socket'];
        }
    }

    /**
     * @param int $role
     * @return $this
     */
    public function setRole($role) {
        $this->role = $role;
        return $this;
    }

    /**
     * @return int
     */
    public function getRole() {
        return $this->role;
    }

    /**
     * @return string
     */
    public function getListen() {
        return $this->listen;
    }

    /**
     * @return array
     */
    public function getServerNames() {
        return $this->serverNames;
    }

    /**
     * @return string
     */
    public function getSocket() {
        return $this->socket;
    }
}
