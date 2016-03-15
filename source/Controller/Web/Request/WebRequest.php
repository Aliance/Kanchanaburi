<?php
namespace Aliance\Kanchanaburi\Controller\Web\Request;

use Aliance\Kanchanaburi\Controller\Request\AbstractRequest;
use Aliance\Kanchanaburi\Controller\Request\AbstractRequestParameter;
use Aliance\Kanchanaburi\Controller\Request\ServerRequestParameter;

/**
 * Web request
 */
class WebRequest extends AbstractRequest {
    /**
     * @var AbstractRequestParameter[]
     */
    private $Parameters = [];

    /**
     * @param AbstractRequestParameter $Parameter
     */
    public function appendParameter(AbstractRequestParameter $Parameter) {
        $type = $Parameter->getType();
        if (!array_key_exists($type, $this->Parameters)) {
            $this->Parameters[$type] = [];
        }
        $this->Parameters[$type][$Parameter->getName()] = $Parameter;
    }

    /**
     * @return AbstractRequestParameter[]
     */
    public function getRequestParameters() {
        return array_merge(
            $this->getParameters(AbstractRequestParameter::TYPE_GET),
            $this->getParameters(AbstractRequestParameter::TYPE_POST)
        );
    }

    /**
     * @param int $type
     * @return AbstractRequestParameter[]
     */
    private function getParameters($type) {
        if (!array_key_exists($type, $this->Parameters)) {
            $this->Parameters[$type] = [];
        }
        return $this->Parameters[$type];
    }

    /**
     * @param string $name
     * @return ServerRequestParameter
     */
    public function getServerParameter($name) {
        return $this->getParameters(AbstractRequestParameter::TYPE_SERVER)[$name];
    }
}
