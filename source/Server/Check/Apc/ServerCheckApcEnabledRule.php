<?php
namespace Aliance\Kanchanaburi\Server\Check;

/**
 * Check APC enable rule
 */
final class ServerCheckApcEnabledRule extends ServerCheckExtensionEnabledRule {
    /**
     * {@inheritdoc}
     */
    protected $enabledCommand = 'php -i | grep "APCu Support" | awk \'{print $4}\'';
}
