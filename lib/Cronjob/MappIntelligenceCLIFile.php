<?php

// @codeCoverageIgnoreStart
require_once __DIR__ . '/MappIntelligenceCLIException.php';
// @codeCoverageIgnoreEnd

/**
 * Class MappIntelligenceCLIFile
 */
class MappIntelligenceCLIFile
{
    /**
     * Constant for temporary file extension name.
     */
    const TEMPORARY_FILE_EXTENSION = ".tmp";
    /**
     * Constant for logfile extension name.
     */
    const LOG_FILE_EXTENSION = ".log";
    /**
     * Constant for the default value of max file duration (30 min).
     */
    const DEFAULT_MAX_FILE_DURATION = 30 * 60 * 1000;

    /**
     * @return int
     */
    private static function getTimestamp()
    {
        return round(microtime(true) * 1000);
    }

    /**
     * @param string $filename File
     *
     * @return int Extract timestamp from file name.
     */
    private static function extractTimestamp($filename)
    {
        $defaultTimestamp = 0;
        $find = preg_replace('/^.+-(\\d{13})\\..+$/', '$1', $filename);

        if (!empty($find)) {
            $defaultTimestamp = intval($find);
        }

        return $defaultTimestamp;
    }

    /**
     * @param string $filePath Path to the file directory
     * @param string $filePrefix Name of the file prefix
     * @param string $ext Name of the file extension
     *
     * @return array
     */
    public static function getFiles($filePath, $filePrefix, $ext)
    {
        $f = scandir($filePath);
        return array_filter($f, function ($name) use ($filePrefix, $ext) {
            return substr_compare($name, $filePrefix, 0, strlen($filePrefix)) === 0
                && substr_compare($name, $ext, -strlen($ext)) === 0;
        });
    }

    /**
     * @param string $file File
     *
     * @return array
     */
    public static function getFileContent($file)
    {
        $fileContent = file_get_contents($file);
        return explode("\n", $fileContent);
    }

    /**
     * @param string $filename File
     * @param string $filePath Path to the file directory
     *
     * @return bool Rename status
     */
    private static function renameFile($filename, $filePath)
    {
        $i = strrpos($filename, '.');
        $name = substr($filename, 0, $i);
        $renamedFile = $name . self::LOG_FILE_EXTENSION;

        return rename($filePath . $filename, $filePath . $renamedFile);
    }

    /**
     * @param string $filePath Path to the file directory
     * @param string $filePrefix Name of the file prefix
     *
     * @return bool
     */
    public static function checkTemporaryFiles($filePath, $filePrefix)
    {
        $renameStatus = false;
        $tmpFiles = self::getFiles($filePath, $filePrefix, self::TEMPORARY_FILE_EXTENSION);
        if (!empty($tmpFiles)) {
            foreach ($tmpFiles as $tmpFile) {
                if (self::getTimestamp() > self::extractTimestamp($tmpFile) + self::DEFAULT_MAX_FILE_DURATION) {
                    $renameStatus = self::renameFile($tmpFile, $filePath);
                }
            }
        }

        return $renameStatus;
    }

    /**
     * @param string $filePath Path to the file directory
     * @param string $filePrefix Name of the file prefix
     *
     * @return array
     * @throws MappIntelligenceCLIException
     */
    public static function getLogFiles($filePath, $filePrefix)
    {
        $files = self::getFiles($filePath, $filePrefix, self::LOG_FILE_EXTENSION);
        if (empty($files)) {
            throw new MappIntelligenceCLIException(MappIntelligenceMessages::$REQUEST_LOG_FILES_NOT_FOUND);
        } else {
            sort($files, SORT_STRING);
        }

        return $files;
    }

    /**
     * @param string $filename File which should be deleted
     */
    public static function deleteFile($filename)
    {
        unlink($filename);
    }
}
