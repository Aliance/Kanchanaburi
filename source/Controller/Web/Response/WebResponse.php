<?php
namespace Aliance\Kanchanaburi\Controller\Web\Response;

use Aliance\Kanchanaburi\Controller\Response\AbstractResponse;
use Aliance\Kanchanaburi\Template\Page\AbstractPageTemplate;

/**
 * Web response
 */
class WebResponse extends AbstractResponse {
    /**
     * @var AbstractPageTemplate
     */
    private $Template;

    /**
     * @param AbstractPageTemplate $Page
     */
    protected function setTemplate(AbstractPageTemplate $Page) {
        $this->Template = $Page;
    }

    /**
     * @return AbstractPageTemplate
     */
    public function getTemplate() {
        return $this->Template;
    }

    /**
     * @param string $output
     */
    public function appendOutput($output) {
        $this->output .= (string) $output;
    }
}
