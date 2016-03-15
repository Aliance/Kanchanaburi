<?php
namespace Aliance\Kanchanaburi\Server;

use Aliance\Kanchanaburi\Config\Config;
use ReflectionClass;

/**
 * Server class
 */
final class Server {
    /**
     * @var string
     */
    const CONSTANT_PREFIX = 'ROLE_';

    /**
     * @var int
     */
    const ROLE_NONE = 0;

    /**
     * @var int
     */
    const ROLE_WEB = 1;

    /**
     * @var int
     */
    const ROLE_CRON = 2;

    /**
     * @var int
     */
    const ROLE_STATICS = 3;

    /**
     * @var array
     */
    private static $allRoles = [];

    /**
     * @var string
     */
    private $secureUser = '';

    /**
     * @var string
     */
    private $cronUser = '';

    /**
     * @var string
     */
    private $name = '';

    /**
     * @var int[]
     */
    private $roles = [];

    /**
     * @var NginxServer[]
     */
    private $NginxServers = [];

    /**
     * @var array
     */
    private $rules = [];

    /**
     * @param string $name
     * @param array $config
     */
    public function __construct($name, array $config) {
        $this->name = $name;
        $this->secureUser = $config['secure_user'];

        $this->setRoles($config['roles']);
        $rolesConfig = Config::getInstance()->get('roles');
        foreach ($this->getRoles() as $role) {
            switch ($role) {
                case Server::ROLE_WEB:
                case Server::ROLE_STATICS:
                    $this->NginxServers[$role] = new NginxServer($role, $config['nginx'][$role]);
                    $rules = $rolesConfig[$role]['rules'];
                    foreach ($rules as $ruleClass => $ruleValue) {
                        $this->rules[$ruleClass . ':' . $role] = new $ruleClass($ruleValue, $this);
                    }
                    break;
                case Server::ROLE_CRON:
                    $this->cronUser = $config['cron'][$role]['user'];
                    break;
            }
        }
    }

    /**
     * @return array
     */
    public function getRules() {
        return $this->rules;
    }

    /**
     * @return string
     */
    public function getSecureUser() {
        return $this->secureUser;
    }

    /**
     * @return string
     */
    public function getCronUser() {
        return $this->cronUser;
    }

    /**
     * @return string
     */
    public function getName() {
        return $this->name;
    }

    /**
     * @param array $roles
     */
    public function setRoles($roles) {
        foreach ($roles as $role) {
            if (self::checkRole($role)) {
                $this->roles[] = $role;
            }
        }
    }

    /**
     * @param int $role
     * @return bool
     */
    public function hasRole($role) {
        return in_array($role, $this->roles);
    }

    /**
     * @return array
     */
    public function getRoles() {
        return $this->roles;
    }

    /**
     * @return string
     */
    public function getSecureConnectionString() {
        return 'ssh ' . $this->getSecureUser() . '@' . $this->getName();
    }

    /**
     * @return NginxServer[]
     */
    public function getNginxServers() {
        return $this->NginxServers;
    }

    /**
     * @param int $role
     * @return bool
     */
    private static function checkRole($role) {
        if (empty(self::$allRoles)) {
            $Reflection = new ReflectionClass(__CLASS__);
            foreach ($Reflection->getConstants() as $name => $value) {
                if (strpos($name, self::CONSTANT_PREFIX) === 0) {
                    self::$allRoles[$value] = $value;
                }
            }
        }
        return array_key_exists($role, self::$allRoles);
    }
}
