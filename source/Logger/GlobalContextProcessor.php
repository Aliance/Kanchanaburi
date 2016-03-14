<?php
namespace Aliance\Kanchanaburi\Logger;

/**
 * Logger post processor
 * Adds global context data to logger context
 */
final class GlobalContextProcessor {
    /**
     * @param array $record
     * @return array
     */
    function __invoke($record) {
        if (!isset($record['context'])) {
            $record['context'] = [];
        }

        foreach (LoggerFactory::getGlobalContext() as $k => $v) {
            $record['context'][$k] = $v;
        }

        return $record;
    }
}
