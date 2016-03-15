<?php
namespace Aliance\Kanchanaburi\Template\Portlet;

use Aliance\Kanchanaburi\Template\AbstractTemplate;

/**
 * Abstract portlet template
 */
abstract class AbstractPortletTemplate extends AbstractTemplate {
    public function __construct() {
        $this->templateFiles[] = sprintf(
            '%s/%s.phtml',
            static::PATH,
            lcfirst(preg_replace('/[\w\d\\\]+\\\([\w\d]+)Template/', '${1}', get_called_class()))
        );
    }

    /**
     * @return string
     */
    protected function getTemplateFile() {
        return sprintf(
            '%s%s.phtml',
            static::PATH,
            lcfirst(get_called_class())
        );
    }
}
