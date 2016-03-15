<?php
namespace Aliance\Kanchanaburi\Controller\Web;

use Aliance\Kanchanaburi\Controller\Request\ServerRequestParameter;
use Aliance\Kanchanaburi\Controller\Web\Request\WebRequest;
use Aliance\Kanchanaburi\Controller\Web\Response\WebResponse;
use BadMethodCallException;
use InvalidArgumentException;

/**
 * Abstract web page controller
 */
abstract class WebPageController extends WebController {
    /**
     * @var string
     */
    const PREFIX_ACTION = 'action';

    /**
     * @var string
     */
    const ACTION_DEFAULT = '';

    /**
     * @var string
     */
    const PREFIX_REQUEST  = 'Request';

    /**
     * @var string
     */
    const PREFIX_RESPONSE = 'Response';

    /**
     * @var string
     */
    private $actionName = '';

    /**
     * @var string
     */
    private $requestClassName = '';

    /**
     * @var string
     */
    private $responseClassName = '';

    /**
     * @return WebRequest
     */
    private function createRequest() {
        if (!class_exists($this->requestClassName)) {
            return new WebRequest();
        }

        if (!is_subclass_of($this->requestClassName, WebRequest::class)) {
            throw new InvalidArgumentException(get_called_class() . ' is not instance of WebRequest');
        }

        /** @var WebRequest $Request */
        $Request = new $this->requestClassName();
        $Request->setApplication($this->getApplication());

        return $Request;
    }

    /**
     * @return WebResponse
     */
    private function createResponse() {
        if (!class_exists($this->responseClassName)) {
            return new WebResponse();
        }

        if (!is_subclass_of($this->responseClassName, WebResponse::class)) {
            throw new InvalidArgumentException('response is not instance of web response');
        }

        /** @var WebResponse $Response */
        $Response = new $this->responseClassName();
        $Response->setApplication($this->getApplication());

        return $Response;
    }

    public function __construct() {
        $this->actionName = self::PREFIX_ACTION . self::ACTION_DEFAULT;
    }

    /**
     * @return self
     */
    public function initialize() {
        $ActionParameter = new ServerRequestParameter('action');
        $postfix = ucfirst($ActionParameter->getValue());
        $actionName = self::PREFIX_ACTION . $postfix;

        if (method_exists($this, $actionName)) {
            $this->actionName = $actionName;

            $this->requestClassName = get_called_class() . $postfix . self::PREFIX_REQUEST;
            $this->responseClassName = get_called_class() . $postfix . self::PREFIX_RESPONSE;

            $Request = $this->createRequest();
            $Response = $this->createResponse();
            $this->setRequest($Request);
            $this->setResponse($Response);
        }

        return $this;
    }

    /**
     * @return bool
     * @throws BadMethodCallException
     */
    public function run() {
        if (empty($this->actionName) || !method_exists($this, $this->actionName)) {
            throw new BadMethodCallException(sprintf('Action %s not found', $this->actionName));
        }

        if (!$this->{$this->actionName}()) {
            return false;
        }

        $Response = $this->getResponse();
        $Template = $Response->getTemplate();
        if ($Template !== NULL) {
            $content = $Template->getContent();
            $Response->appendOutput($content);
        }
        $Response->display();

        return true;
    }
}
