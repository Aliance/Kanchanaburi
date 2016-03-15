<?php
namespace Aliance\Kanchanaburi\Controller\Request;

/**
 * Abstract request parameter
 */
abstract class AbstractRequestParameter {
    /**
     * @var int
     */
    const TYPE_OTHER = 0;

    /**
     * @var int
     */
    const TYPE_SERVER = 1;

    /**
     * @var int
     */
    const TYPE_GET = 2;

    /**
     * @var int
     */
    const TYPE_POST = 3;

    /**
     * @var int
     */
    const TYPE_HEADER = 4;

    /**
     * @var int
     */
    const TYPE_RAW_BODY = 5;

    /**
     * @var string
     */
    private $name = '';

    /**
     * @var int parameter type ID
     */
    protected $type = self::TYPE_OTHER;

    /**
     * @var mixed
     */
    protected $value;

    /**
     * @param string $name parameter name
     */
    public function __construct($name) {
        $this->name = (string) $name;
    }

    /**
     * @return string
     */
    public function getName() {
        return $this->name;
    }

    /**
     * @return int
     */
    public function getType() {
        return $this->type;
    }

    /**
     * @return mixed
     */
    public function getValue() {
        return $this->value;
    }
}
