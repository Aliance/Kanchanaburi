<?php
namespace Aliance\Kanchanaburi\Cli;

/**
 * Help option class
 */
final class HelpOption extends Option {
    /**
     * @var string
     */
    const NAME_LONG = 'help';

    /**
     * @var string
     */
    const NAME_SHORT = 'h';

    /**
     * @param string $description
     */
    public function __construct($description = '') {
        parent::__construct(self::NAME_LONG, self::NAME_SHORT, $description);
    }
}
