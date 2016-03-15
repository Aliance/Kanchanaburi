<?php
namespace Aliance\Kanchanaburi\Config;

use Aliance\Kanchanaburi\Config\Exception\ConfigFileNotFoundException;
use Aliance\Kanchanaburi\Config\Exception\ConfigKeyLimitReachedException;
use Aliance\Kanchanaburi\Traits\SingletonTrait;

/**
 * Configuration
 */
final class Config {
    use SingletonTrait;

    /**
     * @var int max config key nesting
     */
    const MAX_NESTING = 5;

    /**
     * @var array
     */
    private $cache = [];

    /**
     * @var bool
     */
    private static $cacheEnabled = false;

    /**
     * @var string
     */
    private static $pathPlatformConfigs = '';

    /**
     * @var string
     */
    private static $pathGlobalConfigs = '';

    /**
     * @param bool $cacheEnabled
     */
    public static function setCacheEnabled($cacheEnabled) {
        self::$cacheEnabled = (bool) $cacheEnabled;
    }

    /**
     * @param string $pathGlobalConfigs
     */
    public static function setPathGlobalConfigs($pathGlobalConfigs) {
        self::$pathGlobalConfigs = (string) $pathGlobalConfigs;
    }

    /**
     * @param string $pathPlatformConfigs
     */
    public static function setPathPlatformConfigs($pathPlatformConfigs) {
        self::$pathPlatformConfigs = (string) $pathPlatformConfigs;
    }

    /**
     * @param string $name
     * @param mixed $default
     * @return mixed
     * @throws ConfigKeyLimitReachedException
     */
    public function get($name, $default = null) {
        $parts = explode('.', $name, self::MAX_NESTING);

        if (count($parts) == self::MAX_NESTING) {
            if (strpos($name, '.') !== false) {
                throw new ConfigKeyLimitReachedException();
            }
        }

        if (!count($parts)) {
            return $default;
        }

        $configName = array_shift($parts);
        if (!(self::$cacheEnabled && array_key_exists($configName, $this->cache))) {
            $config = $this->load($configName);
            if (self::$cacheEnabled) {
                $this->cache[$configName] = $config;
            }
        } else {
            $config = $this->cache[$configName];
        }

        return $this->getValue($config, $parts, $default);
    }

    /**
     * @param string $configName
     * @return array
     * @throws ConfigFileNotFoundException
     */
    private function load($configName) {
        $fileName = self::$pathPlatformConfigs . $configName . '.php';
        if (!file_exists($fileName)) {
            $fileName = self::$pathGlobalConfigs . $configName . '.php';
        }

        if (!file_exists($fileName)) {
            throw new ConfigFileNotFoundException($fileName);
        }

        return include($fileName);
    }

    /**
     * @param array $config
     * @param array $parts
     * @param mixed $default
     * @return mixed
     */
    private function getValue($config, $parts, $default) {
        $temp = $config;
        foreach ($parts as $key) {
            if (array_key_exists($key, $temp)) {
                $temp = $temp[$key];
            } else {
                return $default;
            }
        }
        return $temp;
    }
}
