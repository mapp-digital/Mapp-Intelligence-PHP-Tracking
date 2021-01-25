<?php

// @codeCoverageIgnoreStart
require_once __DIR__ . '/MappIntelligenceCLIException.php';
require_once __DIR__ . '/MappIntelligenceCLIFile.php';
require_once __DIR__ . '/../MappIntelligenceMessages.php';
require_once __DIR__ . '/../Config/MappIntelligenceProperties.php';
require_once __DIR__ . '/../Queue/MappIntelligenceQueue.php';
// @codeCoverageIgnoreEnd

class MappIntelligenceCLIFileTransmitter
{
    /**
     * Constant for exit status successful.
     */
    const EXIT_STATUS_SUCCESS = 0;
    /**
     * Constant for exit status fail.
     */
    const EXIT_STATUS_FAIL = 1;

    /**
     * Mapp Intelligence config map
     */
    private $cfg;
    /**
     * The path to your request logging file.
     */
    private $filename;

    /**
     * MappIntelligenceCLIFileTransmitter constructor.
     * @param array $cfg
     */
    public function __construct($cfg)
    {
        $this->cfg = $cfg;
        $this->filename = $this->cfg[MappIntelligenceProperties::FILE_NAME];
    }

    /**
     * @return int exit status
     */
    private function sendRequests()
    {
        $tmpFilename = dirname($this->filename) . '/MappIntelligenceRequests-' . rand() . '.log';
        $renamingError = sprintf(MappIntelligenceMessages::$RENAMING_FAILED, $this->filename, $tmpFilename);
        try {
            if (!rename($this->filename, $tmpFilename)) {
                throw new MappIntelligenceCLIException($renamingError);
            }
        } catch (Exception $e) {
            return $e->getMessage();
        }

        $fileLines = MappIntelligenceCLIFile::getFileContent($tmpFilename);
        $requestQueue = new MappIntelligenceQueue($this->cfg);
        for ($i = 0, $j = count($fileLines); $i < $j; $i++) {
            if (!trim($fileLines[$i])) {
                continue;
            }

            $requestQueue->add($fileLines[$i]);
        }

        $status = self::EXIT_STATUS_SUCCESS;
        if (!$requestQueue->flush()) {
            $this->cfg[MappIntelligenceProperties::CONSUMER_TYPE] = MappIntelligenceConsumerType::FILE;
            $this->cfg[MappIntelligenceProperties::FILE_MODE] = 'c';

            $config = new MappIntelligenceConfig($this->cfg);
            $fileQueue = new MappIntelligenceQueue($config->build());
            $requests = $requestQueue->getQueue();

            for ($i = 0, $j = count($requests); $i < $j; $i++) {
                $fileQueue->add($requests[$i]);
            }

            $status = self::EXIT_STATUS_FAIL;
        }

        MappIntelligenceCLIFile::deleteFile($tmpFilename);

        return $status;
    }

    /**
     * @return int|string
     */
    public function send()
    {
        if (!file_exists($this->filename)) {
            return sprintf(MappIntelligenceMessages::$REQUEST_LOG_FILES_NOT_FOUND, $this->filename);
        }

        return $this->sendRequests();
    }
}
