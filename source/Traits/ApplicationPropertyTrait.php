<?php
namespace Aliance\Kanchanaburi\Traits;

use Aliance\Kanchanaburi\Application\AbstractApplication;

/**
 * Application property trait
 */
trait ApplicationPropertyTrait {
    /**
     * @var AbstractApplication
     */
    private $Application;

    /**
     * @return AbstractApplication
     */
    public function getApplication() {
        return $this->Application;
    }

    /**
     * @param AbstractApplication $Application
     * @return $this
     */
    public function setApplication(AbstractApplication $Application) {
        $this->Application = $Application;
        return $this;
    }
}
