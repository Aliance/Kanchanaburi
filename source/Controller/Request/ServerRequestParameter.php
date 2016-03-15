<?php
namespace Aliance\Kanchanaburi\Controller\Request;

use OutOfBoundsException;

/**
 * Server request parameter
 */
final class ServerRequestParameter extends AbstractRequestParameter {
    /**
     * {@inheritdoc}
     */
    protected $type = self::TYPE_SERVER;

    /**
     * {@inheritdoc}
     * @throws OutOfBoundsException
     */
    public function __construct($name) {
        parent::__construct($name);

        if (!array_key_exists($name, $_SERVER)) {
            throw new OutOfBoundsException(sprintf('Server parameter %s not found', $name));
        }

        $this->value = $_SERVER[$name];
    }
}
