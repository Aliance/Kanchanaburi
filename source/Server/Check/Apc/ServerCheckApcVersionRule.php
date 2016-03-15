<?php
namespace Aliance\Kanchanaburi\Server\Check;

/**
 * Check APC version rule
 */
final class ServerCheckApcVersionRule extends ServerCheckExtensionVersionRule {
    /**
     * {@inheritdoc}
     */
    protected $versionCommand = 'php -i | grep -A4 ^apcu$ | fgrep Version | awk \'{print $3}\'';
}
