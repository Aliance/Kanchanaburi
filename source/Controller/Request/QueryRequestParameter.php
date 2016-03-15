<?php
namespace Aliance\Kanchanaburi\Controller\Request;

use OutOfBoundsException;

/**
 * Query request parameter
 */ 
final class QueryRequestParameter extends AbstractRequestParameter {
    /**
     * {@inheritdoc}
     */
    protected $type = self::TYPE_GET;

    /**
     * {@inheritdoc}
     * @throws OutOfBoundsException
     */
    public function __construct($name) {
        parent::__construct($name);

        if (!array_key_exists($name, $_GET)) {
            throw new OutOfBoundsException(sprintf('Query parameter %s not found', $name));
        }

        $this->value = $_GET[$name];
    }
}
