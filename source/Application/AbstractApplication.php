<?php
namespace Aliance\Kanchanaburi\Application;

/**
 * Abstract application class
 */
abstract class AbstractApplication {
    /**
     * Initialize application
     */
    abstract public function initialize();

    /**
     * Run application
     */
    abstract public function run();
}
