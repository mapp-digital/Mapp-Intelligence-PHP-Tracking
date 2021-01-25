<?php

require_once __DIR__ . '/MappIntelligenceAbstractConsumer.php';
require_once __DIR__ . '/MappIntelligenceConsumerFileRotationException.php';
require_once __DIR__ . '/MappIntelligenceConsumerType.php';
require_once __DIR__ . '/../MappIntelligenceMessages.php';

/**
 * Class MappIntelligenceConsumerFile
 */
class MappIntelligenceConsumerFileRotation extends MappIntelligenceAbstractConsumer
{
    const TEMPORARY_FILE_EXTENSION = ".tmp";
    const LOG_FILE_EXTENSION = ".log";
    const DEFAULT_MAX_FILE_LINES = 10 * 1000;
    const DEFAULT_MAX_FILE_DURATION = 30 * 60 * 1000;
    const DEFAULT_MAX_FILE_SIZE = 24 * 1024 * 1024;

    private $filePath;
    private $filePrefix;
    private $maxFileLines;
    private $maxFileDuration;
    private $maxFileSize;
    /**
     * @var false|resource|null
     */
    private $file;
    /**
     * @var string
     */
    private $filename;
    /**
     * @var int
     */
    private $currentFileLines;
    /**
     * @var int
     */
    private $timestamp;

    /**
     * @var string
     */
    protected $type = MappIntelligenceConsumerType::FILE_ROTATION;

    /**
     * MappIntelligenceConsumerFile constructor.
     * @param array $config
     */
    public function __construct($config = array())
    {
        parent::__construct($config);

        $this->filePath = $config["filePath"];
        $this->filePrefix = $config["filePrefix"];
        $this->maxFileLines = $this->getValueOrDefault($config["maxFileLines"], self::DEFAULT_MAX_FILE_LINES);
        $this->maxFileDuration = $this->getValueOrDefault($config["maxFileDuration"], self::DEFAULT_MAX_FILE_DURATION);
        $this->maxFileSize = $this->getValueOrDefault($config["maxFileSize"], self::DEFAULT_MAX_FILE_SIZE);

        $this->searchWriteableFile();
    }

    /**
     *
     */
    public function __destruct()
    {
        $this->close();
    }

    /**
     *
     */
    private function close()
    {
        if (!empty($this->file) && is_resource($this->file)) {
            fclose($this->file);
        }
    }

    /**
     * @param mixed $value
     * @param mixed $default
     * @return mixed
     */
    private function getValueOrDefault($value, $default)
    {
        return !empty($value) ? $value : $default;
    }

    /**
     * @return int
     */
    private function getTimestamp()
    {
        return round(microtime(true) * 1000);
    }

    /**
     * @param string $path absolute file path
     * @return false|resource|null
     */
    private function getFileResource($path)
    {
        if ($this->file) {
            $this->close();
        }

        $f = null;
        try {
            $f = fopen($path, 'a++');
        } catch (Exception $e) {
            $this->logger->log(MappIntelligenceMessages::$GENERIC_ERROR, $e->getFile(), $e->getMessage());
        }

        return $f;
    }

    /**
     * Create new temporary file.
     */
    private function createNewTempFile()
    {
        $this->currentFileLines = 0;
        $this->timestamp = $this->getTimestamp();

        $this->filename = $this->filePrefix . '-' . $this->timestamp . self::TEMPORARY_FILE_EXTENSION;
        $absolutePath = $this->filePath . $this->filename;

        if (file_exists($absolutePath)) {
            $this->logger->log(MappIntelligenceMessages::$USE_EXISTING_LOG_FILE, $this->filename, $absolutePath);
        } else {
            $this->logger->log(MappIntelligenceMessages::$CREATE_NEW_LOG_FILE, $this->filename, $absolutePath, 'true');
        }

        $this->file = $this->getFileResource($absolutePath);
    }

    /**
     * @return int Number of current lines.
     */
    private function getCurrentFileLines()
    {
        $lines = 0;

        try {
            if (!empty($this->file) && is_resource($this->file)) {
                while ((fgets($this->file)) !== false) {
                    $lines++;
                }
            } else {
                $message = sprintf(MappIntelligenceMessages::$IS_NOT_A_VALID_RESOURCE, $this->filename, $this->file);
                throw new MappIntelligenceConsumerFileRotationException($message);
            }
        } catch (Exception $e) {
            $this->logger->log(MappIntelligenceMessages::$GENERIC_ERROR, $e->getFile(), $e->getMessage());
        }

        return $lines;
    }

    /**
     * @return int Extract timestamp from file name.
     */
    private function extractTimestamp()
    {
        $defaultTimestamp = 0;
        $find = preg_replace('/^.+-(\\d{13})\\..+$/', '$1', $this->filename);

        if (!empty($find)) {
            $defaultTimestamp = intval($find);
        }

        return $defaultTimestamp;
    }

    /**
     * Search for writeable file.
     */
    private function searchWriteableFile()
    {
        if (is_dir($this->filePath)) {
            $f = scandir($this->filePath);
            $files = array_filter($f, function ($name) {
                $tfe = self::TEMPORARY_FILE_EXTENSION;
                return substr_compare($name, $this->filePrefix, 0, strlen($this->filePrefix)) === 0
                    && substr_compare($name, $tfe, -strlen($tfe)) === 0;
            });

            if (empty($files)) {
                $this->createNewTempFile();
            } else {
                $this->filename = reset($files);
                $this->file = $this->getFileResource($this->filePath . $this->filename);
                $this->currentFileLines = $this->getCurrentFileLines();
                $this->timestamp = $this->extractTimestamp();
            }
        } else {
            $this->logger->log(MappIntelligenceMessages::$DIRECTORY_NOT_EXIST, $this->filePath);
        }
    }

    /**
     * Rename temporary file (.tmp to .log).
     */
    private function renameAndCreateNewTempFile()
    {
        $i = strrpos($this->filename, '.');
        $name = substr($this->filename, 0, $i);

        $renamedFile = $name . self::LOG_FILE_EXTENSION;
        try {
            if (rename($this->filePath . $this->filename, $this->filePath . $renamedFile)) {
                $this->searchWriteableFile();
                return;
            } else {
                $this->logger->log(MappIntelligenceMessages::$CANNOT_RENAME_TEMPORARY_FILE);
            }
        } catch (Exception $e) {
            $this->logger->log(MappIntelligenceMessages::$GENERIC_ERROR, $e->getFile(), $e->getMessage());
        }

        $this->createNewTempFile();
    }

    /**
     * @param int batchContentSize Batch content size
     *
     * @return bool Is file larger than 24MB or more than 10k lines or is older than 30min.
     * @throws Exception
     */
    private function isFileLimitReached($batchContentSize)
    {
        $lineLimitReached = $this->currentFileLines + $batchContentSize > $this->maxFileLines;
        $durationReached = $this->getTimestamp() > $this->timestamp + $this->maxFileDuration;

        $fileSizeReached = true;
        if (!empty($this->file) && is_resource($this->file)) {
            $fileSizeReached = fstat($this->file)['size'] > $this->maxFileSize;
        } else {
            $message = sprintf(MappIntelligenceMessages::$IS_NOT_A_VALID_RESOURCE, $this->filename, $this->file);
            throw new MappIntelligenceConsumerFileRotationException($message);
        }

        return $lineLimitReached || $durationReached || $fileSizeReached;
    }

    /**
     * @param array $batchContent
     *
     * @return bool
     */
    public function sendBatch(array $batchContent)
    {
        $status = false;
        if (empty($this->file)) {
            return $status;
        }

        $payload = $this->verifyPayload($batchContent);
        if (empty($payload)) {
            return $status;
        }

        $payload .= "\n";

        $bcs = count($batchContent);
        try {
            if ($this->isFileLimitReached($bcs)) {
                $this->renameAndCreateNewTempFile();
            }

            $this->currentFileLines += $bcs;

            fwrite($this->file, $payload);

            $currentBatchSize = count($batchContent);
            $this->logger->log(MappIntelligenceMessages::$WRITE_BATCH_DATA, $this->filename, $currentBatchSize);

            $status = true;
        } catch (Exception $e) {
            $this->logger->log(MappIntelligenceMessages::$GENERIC_ERROR, $e->getFile(), $e->getMessage());
        }

        return $status;
    }
}
