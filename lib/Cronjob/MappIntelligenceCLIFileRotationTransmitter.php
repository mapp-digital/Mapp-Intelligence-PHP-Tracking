<?php

// @codeCoverageIgnoreStart
require_once __DIR__ . '/MappIntelligenceCLIException.php';
require_once __DIR__ . '/MappIntelligenceCLIFile.php';
require_once __DIR__ . '/../MappIntelligenceMessages.php';
require_once __DIR__ . '/../Config/MappIntelligenceProperties.php';
require_once __DIR__ . '/../Queue/MappIntelligenceQueue.php';
// @codeCoverageIgnoreEnd

class MappIntelligenceCLIFileRotationTransmitter
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
     * The path to your request logging files.
     */
    private $filePath;
    /**
     * The prefix name for your request logging file.
     */
    private $filePrefix;
    /**
     * Activates the debug mode.
     * @var MappIntelligenceDebugLogger
     */
    private $logger;

    /**
     * MappIntelligenceCLIFileRotationTransmitter constructor.
     * @param array $cfg
     */
    public function __construct($cfg)
    {
        $this->cfg = $cfg;
        $this->filePath = $this->cfg[MappIntelligenceProperties::FILE_PATH];
        $this->filePrefix = $this->cfg[MappIntelligenceProperties::FILE_PREFIX];
        $this->logger = $this->cfg[MappIntelligenceProperties::LOGGER];
    }

    /**
     * @param array $files List of files
     *
     * @return int exit status
     */
    private function sendRequests($files)
    {
        foreach ($files as $file) {
            $fileLines = MappIntelligenceCLIFile::getFileContent($this->filePath . $file);

            $requestQueue = new MappIntelligenceQueue($this->cfg);
            for ($i = 0, $j = count($fileLines); $i < $j; $i++) {
                if (!trim($fileLines[$i])) {
                    continue;
                }

                $requestQueue->add($fileLines[$i]);
            }

            if (!$requestQueue->flush()) {
                return self::EXIT_STATUS_FAIL;
            }

            MappIntelligenceCLIFile::deleteFile($this->filePath . $file);
        }

        return self::EXIT_STATUS_SUCCESS;
    }

    /**
     * @return int
     */
    public function send()
    {
        if (MappIntelligenceCLIFile::checkTemporaryFiles($this->filePath, $this->filePrefix)) {
            $this->logger->error(MappIntelligenceMessages::$RENAME_EXPIRED_TEMPORARY_FILE);
        }

        try {
            $files = MappIntelligenceCLIFile::getLogFiles($this->filePath, $this->filePrefix);
        } catch (MappIntelligenceCLIException $e) {
            $this->logger->info($e->getMessage());
            return self::EXIT_STATUS_FAIL;
        }

        return $this->sendRequests($files);
    }
}
