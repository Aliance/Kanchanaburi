<?php
namespace Aliance\Kanchanaburi\Autoload;

use Aliance\Kanchanaburi\Error\Exception\GCCacheEntryException;

/**
 * Autoloader for classes
 */
final class Autoloader {
    /**
     * @param string $className
     */
    public function __autoload($className) {
        $fileName = AutoloadMap::getInstance()->get($className);

        if (empty($fileName)) {
            return;
        }

        try {
            include $fileName;
        } catch (GCCacheEntryException $Ex) {
            // APC fail - try another one time
            include $fileName;
        }
    }

    /**
     * Register autoloader
     */
    public static function register() {
        spl_autoload_register([new static(), '__autoload']);
    }
}
