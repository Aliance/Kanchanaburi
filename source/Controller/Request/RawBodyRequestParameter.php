<?php
namespace Aliance\Kanchanaburi\Controller\Request;

/**
 * Raw body request parameter
 */ 
final class RawBodyRequestParameter extends AbstractRequestParameter {
    /**
     * {@inheritdoc}
     */
    protected $type = self::TYPE_RAW_BODY;

    /**
     * {@inheritdoc}
     */
    public function __construct($name) {
        parent::__construct($name);
        $this->value = file_get_contents('php://input');
    }
}
