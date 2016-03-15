<?php
namespace Aliance\Kanchanaburi\Server\Check\PhpRedis;

use Aliance\Kanchanaburi\Server\Check\ServerCheckExtensionEnabledRule;

/**
 * Check PHP Redis enable rule
 */
final class ServerCheckPhpRedisEnabledRule extends ServerCheckExtensionEnabledRule {
    /**
     * @var string
     */
    protected $enabledCommand = 'php -i | grep "Redis Support" | awk \'{print $4}\'';
}
