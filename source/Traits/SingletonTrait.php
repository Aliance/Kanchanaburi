<?php
namespace Aliance\Kanchanaburi\Traits;

/**
 * Singleton trait
 */
trait SingletonTrait {
    /**
     * @var static
     */
    private static $Instance;

    /**
     * @return static
     */
    public static function getInstance() {
        return self::$Instance ?: self::$Instance = new static();
    }

    private function __construct() {}

    private function __clone() {}
}
