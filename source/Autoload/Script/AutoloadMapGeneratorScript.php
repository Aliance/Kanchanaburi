<?php
namespace Aliance\Kanchanaburi\Autoload\Script;

use Aliance\Kanchanaburi\Autoload\AutoloadMap;
use Aliance\Kanchanaburi\Controller\Script\ScriptController;

/**
 * Autoload map generator script
 */
final class AutoloadMapGeneratorScript extends ScriptController {
    public function run() {
        AutoloadMap::getInstance()->store();

        echo 'Classes generation has been completed', PHP_EOL;

        return true;
    }
}
