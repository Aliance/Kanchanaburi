<?php
namespace Aliance\Kanchanaburi\Application;

use Aliance\Kanchanaburi\Controller\Request\ServerRequestParameter;
use Aliance\Kanchanaburi\Controller\Web\WebController;
use InvalidArgumentException;

/**
 * Web application
 */
class WebApplication extends AbstractApplication {
    /**
     * @var string
     */
    private $pageName = '';

    /**
     * @var string
     */
    private $pageClassName = '';

    /**
     * {@inheritdoc}
     */
    public function initialize() {
        // get controller class from nginx parameter
        $this->setPageName((new ServerRequestParameter('page'))->getValue());
    }

    /**
     * {@inheritdoc}
     * @throws InvalidArgumentException
     */
    public function run() {
        $pageClassName = $this->getPageClassName();

        if (!class_exists($pageClassName)) {
            throw new InvalidArgumentException('Page "' . $this->getPageName() . '" not found');
        }

        if (!is_subclass_of($pageClassName, WebController::class)) {
            throw new InvalidArgumentException('"' . $this->getPageName() . '" is not a page');
        }

        /** @var $Controller WebController */
        $Controller = new $pageClassName();
        $Controller->setApplication($this);
        $Controller->initialize();

        return $Controller->run();
    }

    /**
     * @param string $pageName
     */
    public function setPageName($pageName) {
        $this->pageName = (string) $pageName;
        $this->pageClassName = str_replace('.', '\\', $pageName);
    }

    /**
     * @return string
     */
    public function getPageName() {
        return $this->pageName;
    }

    /**
     * @return string
     */
    public function getPageClassName() {
        return $this->pageClassName;
    }
}
