<?php
namespace Aliance\Kanchanaburi\Server\Check\PhpRedis;

use Aliance\Kanchanaburi\Server\Check\ServerCheckExtensionVersionRule;

/**
 * Check PHP Redis version rule
 */
final class ServerCheckPhpRedisVersionRule extends ServerCheckExtensionVersionRule {
    /**
     * @var string
     */
    protected $versionCommand = 'php -i | grep "Redis Version" | awk \'{print $4}\'';
}
