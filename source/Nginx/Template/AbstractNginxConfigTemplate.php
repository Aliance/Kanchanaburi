<?php
namespace Aliance\Kanchanaburi\Nginx\Template;

use Aliance\Kanchanaburi\Config\Config;
use Aliance\Kanchanaburi\Server\NginxServer;
use Aliance\Kanchanaburi\Template\AbstractTemplate;

/**
 * Abstract nginx config template
 */
abstract class AbstractNginxConfigTemplate extends AbstractTemplate {
    /**
     * @var NginxServer
     */
    private $NginxServer;

    /**
     * @var string listen socket for web requests
     */
    public $listen = '';

    /**
     * @var string[] массив наименований поддерживаемых хостов
     */
    public $names = [];

    /**
     * @var string connection string to FastCGI socket
     */
    public $fastcgi = '';

    /**
     * @param NginxServer $NginxServer экземпляр сервера nginx
     */
    public function __construct(NginxServer $NginxServer) {
        $this->NginxServer = $NginxServer;
        $this->listen = $this->NginxServer->getListen();
        $this->names = $this->NginxServer->getServerNames();
        $this->fastcgi = $this->NginxServer->getSocket();
        $rolesConfig = Config::getInstance()->get('roles');
        $this->templateFiles[] = $rolesConfig[$NginxServer->getRole()]['nginx'];
    }
}
