<?php
namespace Aliance\Kanchanaburi\Controller\Script;

use Aliance\Kanchanaburi\Traits\ApplicationPropertyTrait;

/**
 * Абстрактный контроллер
 */
abstract class AbstractController {
    use ApplicationPropertyTrait;

    /**
     * Initialize controller
     */
    abstract public function initialize();

    /**
     * Run controller
     * @return self
     */
    abstract public function run();
}
