<?php
namespace Aliance\Kanchanaburi\Server\Check\PhpRedis;

use Aliance\Kanchanaburi\Server\Check\ServerCheckExtensionRule;

/**
 * Check PHP Redis extension rule
 */
final class ServerCheckPhpRedisExtensionRule extends ServerCheckExtensionRule {
    /**
     * {@inheritdoc}
     */
    protected $extensionName = 'redis';
}
