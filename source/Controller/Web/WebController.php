<?php
namespace Aliance\Kanchanaburi\Controller\Web;

use Aliance\Kanchanaburi\Controller\Script\AbstractController;
use Aliance\Kanchanaburi\Controller\Web\Request\WebRequest;
use Aliance\Kanchanaburi\Controller\Web\Response\WebResponse;

/**
 * Abstract web controller
 */
abstract class WebController extends AbstractController {
    /**
     * @var WebRequest
     */
    private $Request;

    /**
     * @var WebResponse
     */
    private $Response;

    /**
     * @param WebRequest $Request
     */
    public function setRequest($Request) {
        $this->Request = $Request;
    }

    /**
     * @return WebRequest
     */
    public function getRequest() {
        return $this->Request;
    }

    /**
     * @param WebResponse $Response
     */
    public function setResponse($Response) {
        $this->Response = $Response;
    }

    /**
     * @return WebResponse
     */
    public function getResponse() {
        return $this->Response;
    }
}
