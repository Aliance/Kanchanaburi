<?php
namespace Aliance\Kanchanaburi\Controller\Response;

use Aliance\Kanchanaburi\Traits\ApplicationPropertyTrait;
use LogicException;
use RuntimeException;

/**
 * Abstract response
 */ 
abstract class AbstractResponse {
    use ApplicationPropertyTrait;

    /**
     * @var int
     */
    const STATUS_OK = 200;

    /**
     * @var int
     */
    const STATUS_MOVED_TEMPORARY = 302;

    /**
     * @var int
     */
    protected $status = self::STATUS_OK;

    /**
     * @var array
     */
    private $headers = [];

    /**
     * @var string
     */
    protected $output = '';

    /**
     * @param string $header
     * @param string $value
     * @param bool $force
     * @throws LogicException
     */
    public function addHeader($header, $value, $force = true) {
        if (!$force && array_key_exists($header, $this->headers)) {
            throw new LogicException(sprintf('Header %s already set', $header));
        }
        $this->headers[$header] = (string) $value;
    }

    /**
     * @param string $header
     * @param string $default
     * @return string
     */
    public function getHeader($header, $default = null) {
        if (array_key_exists($header, $this->headers)) {
            return $this->headers[$header];
        }
        return $default;
    }

    /**
     * Геттер заголовков ответа
     * @return array массив заголовков ответа
     */
    public function getHeaders() {
        return $this->headers;
    }

    /**
     * @throws RuntimeException если выключена буферизация ответа
     */
    public function __construct() {
        if (!ob_start()) {
            throw new RuntimeException('Can not start output buffering');
        }
    }

    public function __destruct() {
        ob_end_flush();
    }

    /**
     * Display output response into stream
     */
    public function display() {
        http_response_code($this->status);
        foreach ($this->headers as $header => $value) {
            header(sprintf('%s: %s', $header, $value));
        }
        echo $this->output;
    }
}
