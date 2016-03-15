<?php
namespace Aliance\Kanchanaburi\Template\Page;

use Aliance\Kanchanaburi\Config\Config;
use Aliance\Kanchanaburi\Template\AbstractTemplate;
use Aliance\Kanchanaburi\Template\Exception\PlaceholderNotFoundException;
use Aliance\Kanchanaburi\Template\Portlet\AbstractPortletTemplate;

/**
 * AbstractPageTemplate
 */
abstract class AbstractPageTemplate extends AbstractTemplate {
    /**
     * @var string
     */
    private $staticsHost = '';

    /**
     * @return string
     */
    protected function getStaticsHost() {
        if (empty($this->staticsHost)) {
            $serverNames = Config::getInstance()->get('static.hosts');
            $hosts = array_flip($serverNames);
            $this->staticsHost = array_rand($hosts);
        }
        return $this->staticsHost;
    }

    public function __construct() {
        $this->templateFiles[] = sprintf(
            '%s/%s.phtml',
            static::PATH,
            lcfirst(preg_replace('/[\w\d\\\]+\\\([\w\d]+)Template/', '${1}', get_called_class()))
        );
    }

    /**
     * Метод установки плейсхолдера
     * @param string $name наименование плейсхолдера
     * @param AbstractPortletTemplate $Portlet экземпляр плейсхолдера для отображения
     * @throws PlaceholderNotFoundException в случае отсутствия предложенного плейсхолдера
     */
    public function setPlaceholder($name, AbstractPortletTemplate $Portlet) {
        if (!property_exists($this, $name)) {
            throw new PlaceholderNotFoundException(sprintf('Placeholder %s not found', $name));
        }

        $this->$name = $Portlet;
    }
}
