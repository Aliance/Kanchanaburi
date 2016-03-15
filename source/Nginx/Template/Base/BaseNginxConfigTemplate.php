<?php
namespace Aliance\Kanchanaburi\Nginx\Template\Base;

use Aliance\Kanchanaburi\Nginx\Template\Statics\StaticsNginxConfigTemplate;
use Aliance\Kanchanaburi\Nginx\Template\Web\WebNginxConfigTemplate;
use Aliance\Kanchanaburi\Server\Server;
use Aliance\Kanchanaburi\Template\AbstractTemplate;

/**
 * Класс шаблона базовой конфигурации nginx под веб и сервис
 */
final class BaseNginxConfigTemplate extends AbstractTemplate {
    /**
     * @var WebNginxConfigTemplate
     */
    public $NginxWeb;

    /**
     * @var StaticsNginxConfigTemplate
     */
    public $NginxStatics;

    /**
     * @param Server $Server экземпляр описания сервера для построения конфигурации nginx
     */
    public function __construct(Server $Server) {
        foreach ($Server->getNginxServers() as $NginxServer) {
            switch ($NginxServer->getRole()) {
                case Server::ROLE_WEB:
                    $this->NginxWeb = new WebNginxConfigTemplate($NginxServer);
                    break;
                case Server::ROLE_STATICS:
                    $this->NginxStatics = new StaticsNginxConfigTemplate($NginxServer);
                    break;
            }
        }
        $this->templateFiles[] = __DIR__ . '/base.nginx.config.phtml';
    }
}
