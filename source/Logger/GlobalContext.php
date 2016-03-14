<?php
namespace Aliance\Kanchanaburi\Logger;

use ArrayIterator;
use IteratorAggregate;

/**
 * Logger global context
 */
final class GlobalContext implements IteratorAggregate {
    /**
     * @var array
     */
    private $info = [];

    /**
     * @param string $key
     * @param string $value
     */
    public function addInfo($key, $value) {
        $this->info[$key] = (string) $value;
    }

    /**
     * @return ArrayIterator
     */
    public function getIterator() {
        return new ArrayIterator($this->info);
    }
}
