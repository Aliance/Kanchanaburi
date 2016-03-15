<?php
namespace Aliance\Kanchanaburi\Controller\Request;

use OutOfBoundsException;

/**
 * Header request parameter
 */ 
final class HeaderRequestParameter extends AbstractRequestParameter {
    /**
     * @var string
     */
    const PREFIX_HEADER = 'HTTP';

    /**
     * {@inheritdoc}
     */
    protected $type = self::TYPE_HEADER;

    /**
     * {@inheritdoc}
     * @throws OutOfBoundsException
     */
    public function __construct($name) {
        parent::__construct($name);

        $key = $this->createKey($name);
        if (!array_key_exists($key, $_SERVER)) {
            throw new OutOfBoundsException(sprintf('Header %s not found', $name));
        }

        $this->value = $_SERVER[$key];
    }

    /**
     * @param string $name
     * @return string
     */
    private function createKey($name) {
        return implode('_', [
            self::PREFIX_HEADER,
            strtoupper(str_replace('-', '_', $name)),
        ]);
    }
}
