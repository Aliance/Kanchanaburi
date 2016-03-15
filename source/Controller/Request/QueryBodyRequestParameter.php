<?php
namespace Aliance\Kanchanaburi\Controller\Request;

use OutOfBoundsException;

/**
 * Query body request parameter
 */ 
final class QueryBodyRequestParameter extends AbstractRequestParameter {
    /**
     * {@inheritdoc}
     */
    protected $type = self::TYPE_POST;

    /**
     * {@inheritdoc}
     * @throws OutOfBoundsException если заданного параметра не найдено
     */
    public function __construct($name) {
        parent::__construct($name);
        if (!array_key_exists($name, $_POST)) {
            throw new OutOfBoundsException(sprintf('Query body parameter %s not found', $name));
        }

        $this->value = $_POST[$name];
    }

    /**
     * @return array
     */
    public static function getParameters() {
        return $_POST;
    }
}
