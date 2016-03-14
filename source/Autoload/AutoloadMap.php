<?php
namespace Aliance\Kanchanaburi\Autoload;

use Aliance\Kanchanaburi\File\File;
use FilesystemIterator;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;

/**
 * Software support for the preservation classes and map files to access them
 */
final class AutoloadMap {
    /**
     * @var string
     */
    const CACHE_PREFIX = 'autoloadmap';

    /**
     * @var array
     */
    private $map = [];

    /**
     * @var AutoloadMap
     */
    private static $Instance;

    /**
     * @var string[]
     */
    private static $classesRoots = [];

    /**
     * @var string
     */
    private static $path = '';

    /**
     * @var string
     */
    private static $fileName = '';

    /**
     * @var string
     */
    private static $projectPrefix = '';

    /**
     * @var bool
     */
    private $hasExtensionAPC = false;

    /**
     * @return AutoloadMap
     */
    public static function getInstance() {
        return self::$Instance ?: self::$Instance = new self();
    }

    /**
     * @param string[] $classesRoot
     */
    public static function setClassesRoots(array $classesRoot) {
        self::$classesRoots = $classesRoot;
    }

    /**
     * @param string $path
     */
    public static function setPath($path) {
        self::$path = (string) $path;
    }

    /**
     * @param string $fileName
     */
    public static function setFileName($fileName) {
        self::$fileName = (string) $fileName;
    }

    /**
     * @param string $prefix
     */
    public static function setProjectPrefix($prefix) {
        self::$projectPrefix = (string) $prefix;
    }

    private function __construct() {
        $this->hasExtensionAPC = extension_loaded('apc');
    }

    /**
     * Returns filename comprising the class
     * @param string $className
     * @return string
     */
    public function get($className) {
        $fileName = $this->getFilenameFromAPC($className);
        if ($fileName !== true) {

            if (empty($this->map)) {
                $this->includeMapFile();
                if (!empty($this->map)) {
                    $this->storeMapToAPC();
                }
            }

            if (empty($this->map)) {
                $this->createMap();
                if (!empty($this->map)) {
                    $this->storeMapToAPC();
                }
            }

            if (isset($this->map[$className])) {
                $fileName = $this->map[$className];
            }
        }
        return (string) $fileName;
    }

    /**
     *
     * Метод сохранения карты загрузки файлов для заданных классов.
     * Если карта пуста, она заливается через данные файловой системы
     */
    public function store() {
        $this->createMap();
        $this->storeMapToFile();
    }

    /**
     * Метод получения имени файла заданного класса через APC
     * @param string $className
     * @return string|bool наименование хранимого файла, либо false, если данных по ключу не было найдено
     */
    private function getFilenameFromAPC($className) {
        if ($this->hasExtensionAPC) {
            $key = self::buildKey($className);
            return apc_fetch($key);
        } else {
            return false;
        }
    }

    /**
     * Метод сохранения данных из загруженной карты с APC
     */
    private function storeMapToAPC() {
        if ($this->hasExtensionAPC) {
            foreach ($this->map as $className => $fileName) {
                $key = self::buildKey($className);
                apc_store($key, $fileName, 3600);
            }
        }
    }

    /**
     * Метод сохранения карты загрузки классов в файл автоматической загрузки
     */
    private function storeMapToFile() {
        $content = '<?php return ' . var_export($this->map, true) . ';';
        File::store(self::$path, self::$fileName, $content);
    }

    /**
     * Метод получения данных карты классов по данным из файла загрузки
     */
    private function includeMapFile() {
        if (file_exists(self::buildFullName())) {
            $this->map = include(self::buildFullName());
        }
    }

    /**
     * Create classes map by filesystem iteration
     */
    private function createMap() {
        foreach (self::$classesRoots as $path) {
            $this->createMapForPath($path);
        }
    }

    /**
     * Create classes map by directory path
     * @param string $path
     */
    private function createMapForPath($path) {
        $DirectoryIterator = new RecursiveDirectoryIterator(
            $path,
            FilesystemIterator::KEY_AS_FILENAME | FilesystemIterator::CURRENT_AS_FILEINFO | FilesystemIterator::SKIP_DOTS
        );
        $Iterator = new RecursiveIteratorIterator($DirectoryIterator, RecursiveIteratorIterator::SELF_FIRST);
        foreach ($Iterator as $fileName => $FileInfo) {
            if (!$FileInfo->isDir()) {
                if ($FileInfo->getExtension() === 'php') {
                    $path = $FileInfo->getRealPath();

                    $namespace = '';
                    $Handler = popen('grep -Eo --max-count=1 "^namespace\s+(.)+;" ' . $path, 'r');
                    if ($Handler !== false) {
                        $string = fgets($Handler);
                        if (!empty($string)) {
                            if (preg_match('/namespace\s+(.+);/', $string, $matches)) {
                                if (isset($matches[1])) {
                                    $namespace .= $matches[1] . '\\';
                                }
                            }
                        }
                    }
                    pclose($Handler);

                    // TODO: can't use these 'reserved' words in phpdoc
                    $Handler = popen('grep -Eo --max-count=1 "(class|interface|trait)\s+(\w|\d)+" ' . $path, 'r');
                    if ($Handler !== false) {
                        $string = fgets($Handler);
                        if (!empty($string)) {
                            if (preg_match('/(class|interface|trait)\s+([\w\d]+)/', $string, $matches)) {
                                if (isset($matches[2])) {
                                    $className = $namespace . $matches[2];
                                    $this->map[$className] = $path;
                                }
                            }
                        }
                    }
                    pclose($Handler);
                }
            }
        }
    }

    /**
     * @return string
     */
    private static function buildFullName() {
        return self::$path . self::$fileName;
    }

    /**
     * @param string $className
     * @return string
     */
    private static function buildKey($className) {
        return self::CACHE_PREFIX . ':' . self::$projectPrefix . ':' . $className;
    }
}
