<?php
namespace Aliance\Kanchanaburi\Cli;
use Aliance\Kanchanaburi\Cli\Exception\DuplicateOptionException;
use Aliance\Kanchanaburi\Cli\Exception\RequiredOptionException;

/**
 * Class for POSIX command line support
 */
final class CommandPosix extends Command {
    /**
     * @var array cache of added parameters
     */
    private $searchStringsCache = [];

    /**
     * Method to build getopt parameter postfix for option
     * @param Option $Option instance of option
     * @return string getopt parameter postfix for option
     */
    private static function getPostfix(Option $Option) {
        $postfix = '';
        if ($Option->getType() == Option::TYPE_STRING) {
            $postfix .= ':';
            if (!$Option->isRequired()) {
                $postfix .= ':';
            }
        }
        return $postfix;
    }

    /**
     * Method to get getopt parameters for command line parsing
     * @return array getopt parameters
     */
    private function getOptionsSearchStrings() {
        if (empty($this->searchStringsCache)) {
            $this->searchStringsCache = [
                0 => '',
                1 => [],
            ];
            foreach ($this->parameters as $Option) {
                $postfix = self::getPostfix($Option);
                /** @var $Option Option */
                $this->searchStringsCache[0] .= $Option->getShort() . $postfix;
                $this->searchStringsCache[1][] = $Option->getLong() . $postfix;
            }
        }
        return $this->searchStringsCache;
    }

    /**
     * Method to clear getopt parameters cache
     */
    protected function clearCache() {
        $this->searchStringsCache = [];
    }

    /**
     * Prepare option value from getopt library
     * @param string|bool $value value from getopt result
     * @return string|bool prepared value
     */
    private function getPreparedValue($value) {
        return $value === false ? true : $value;
    }

    /**
     * POSIX parse command line method
     * @param bool $panic throw exception about required option absence
     * @throws DuplicateOptionException when option value duplicated
     * @throws RequiredOptionException when required options is not set
     */
    public function parse($panic = false) {
        list($shorts, $longs) = $this->getOptionsSearchStrings();
        $options = getopt($shorts, $longs);
        $Exception = null;
        foreach ($this->parameters as $Option) {
            /** @var $Option Option */
            $short = $Option->getShort();
            $long = $Option->getLong();
            switch (true) {
                case isset($options[$short]) && isset($options[$long]):
                    throw new DuplicateOptionException();
                case isset($options[$short]):
                    $this->setOptionValue($Option, $this->getPreparedValue($options[$short]));
                    break;
                case isset($options[$long]):
                    $this->setOptionValue($Option, $this->getPreparedValue($options[$long]));
                    break;
                case $Option->isRequired():
                    $Exception = new RequiredOptionException($long);
            }
        }

        if ($panic && $Exception !== NULL) {
            throw $Exception;
        }
    }

    /**
     * Display command help
     */
    public function displayHelp() {
        $string = 'Using: ' . $this->script . ' ' . $this->command . ' ';
        $where = [];
        foreach ($this->parameters as $Option) {
            /** @var $Option Option */
            $short = $Option->getShort();
            $long = $Option->getLong();
            $temp = '-' . $short . '|--' . $long;
            if (!$Option->isRequired()) {
                $string .= '[' . $temp . '] ';
            } else {
                $string .= $temp . ' ';
            }

            $where[] = '-' . $short . ', --' . $long . '  - ' . $Option->getDescription();
        }
        $string .= "\n";
        $string .= implode("\n", $where) . "\n";
        echo $string;
        exit(0);
    }

    /**
     * Method to set option value and call event handler if needed
     * @param Option $Option instance of option
     * @param bool|string $value option value
     */
    private function setOptionValue(Option $Option, $value) {
        $Option->setValue($value);
        $long = $Option->getLong();
        if (isset($this->events[$long])) {
            $Handler = $this->events[$long];
            $Handler($long, $value);
        }
    }
}
