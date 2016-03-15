<?php
namespace Aliance\Kanchanaburi\Template;

use Aliance\Kanchanaburi\Template\Exception\TemplateException;
use RuntimeException;

/**
 * Abstract template
 */
abstract class AbstractTemplate {
    /**
     * @var array
     */
    protected $templateFiles = [];

    /**
     * @return string
     * @throws RuntimeException
     */
    public function getContent() {
        if (!ob_start()) {
            throw new RuntimeException('cannot start output buffering');
        }

        foreach ($this->templateFiles as $templateFile) {
            $this->parseTemplate($templateFile);
        }

        return ob_get_clean();
    }

    /**
     * @param string $templateFileName
     * @throws TemplateException
     */
    private function parseTemplate($templateFileName) {
        if (!file_exists($templateFileName)) {
            throw new TemplateException(sprintf('template %s not found', $templateFileName));
        }

        include $templateFileName;
    }
}
