<?php
namespace Aliance\Kanchanaburi\File;

/**
 * Filesystem wrapper class
 */
final class File {
    /**
     * Atomic file save
     * @param string $path
     * @param string $fileName
     * @param mixed $content
     */
    public static function store($path, $fileName, $content) {
        system('mkdir -p ' . escapeshellarg($path));

        $tmpFileName = $path . $fileName . '.' . getmypid() . '.tmp';

        file_put_contents($tmpFileName, $content);

        rename($tmpFileName, $path . $fileName);
    }
}
