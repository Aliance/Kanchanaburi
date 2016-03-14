<?php
namespace Aliance\Kanchanaburi\Nginx\Script;

use Aliance\Kanchanaburi\Cli\Option;
use Aliance\Kanchanaburi\Controller\Script\ScriptController;
use Aliance\Kanchanaburi\File\File;

/**
 * Creates nginx config from template
 */
final class CreateNginxConfigScript extends ScriptController {
    /**
     * @var string
     */
    private $serverId = '';

    public function initialize() {
        $this->setServerId(constant('APPLICATION_SERVER'));

        $this->options = [
            new Option('serverId', 'a', 'server identifier for configuration', Option::TYPE_STRING, false),
        ];

        parent::initialize();
    }

    public function run() {
        $Server = ServerFactory::createServer($this->getServerId());
        if ($Server === NULL) {
            echo 'Server configuration for "', $this->getServerId(), '" not found', PHP_EOL;
            return false;
        }

        $Template = new BaseNginxConfigTemplate($Server);
        File::store(constant('PATH_DYNAMIC'), constant('FILE_NGINX_CONFIG'), $Template->getContent());

        echo 'Nginx config has been created', PHP_EOL;

        return true;
    }

    /**
     * @param string $serverId
     */
    public function setServerId($serverId) {
        $this->serverId = (string) $serverId;
    }

    /**
     * @return string
     */
    public function getServerId() {
        return $this->serverId;
    }
}
