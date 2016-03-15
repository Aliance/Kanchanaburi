<?php
namespace Aliance\Kanchanaburi\Server;

use Aliance\Kanchanaburi\Config\Config;
use Aliance\Kanchanaburi\Server\Exception\ServerNotFoundException;

/**
 * Server factory
 */
final class ServerFactory {
    /**
     * @var array
     */
    private static $servers = [];

    /**
     * @return Server[]
     */
    public static function createServers() {
        $Servers = [];
        foreach (self::getServers() as $serverName => $serverConfig) {
            $Servers[$serverName] = new Server($serverName, $serverConfig);
        }
        return $Servers;
    }

    /**
     * @param string $serverName
     * @return Server
     * @throws ServerNotFoundException
     */
    public static function createServer($serverName) {
        $servers = self::getServers();

        if (!array_key_exists($serverName, $servers)) {
            throw new ServerNotFoundException($serverName);
        }

        return new Server($serverName, $servers[$serverName]);
    }

    /**
     * @return array
     */
    private static function getServers() {
        return self::$servers ?: self::$servers = Config::getInstance()->get('servers');
    }
}
