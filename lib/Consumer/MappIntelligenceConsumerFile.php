<?php

require_once __DIR__ . '/MappIntelligenceAbstractConsumer.php';
require_once __DIR__ . '/MappIntelligenceConsumerType.php';
require_once __DIR__ . '/../MappIntelligenceMessages.php';

/**
 * Class MappIntelligenceConsumerFile
 */
class MappIntelligenceConsumerFile extends MappIntelligenceAbstractConsumer
{
    /**
     * @var string
     */
    protected $type = MappIntelligenceConsumerType::FILE;
    /**
     * @var resource
     */
    private $handle;
    /**
     * @var string
     */
    private $filename;
    /**
     * @var string
     */
    private $fileMode;

    /**
     * MappIntelligenceConsumerFile constructor.
     * @param array $config
     */
    public function __construct($config = array())
    {
        parent::__construct($config);

        $this->filename = $config['filename'];
        $this->fileMode = $config['fileMode'];

        try {
            $this->handle = fopen($this->filename, $this->fileMode);
        } catch (Exception $e) {
            $this->logger->log(MappIntelligenceMessages::$GENERIC_ERROR, $e->getFile(), $e->getMessage());
        }
    }

    /**
     *
     */
    public function __destruct()
    {
        if ($this->handle) {
            fclose($this->handle);
        }
    }

    /**
     * @param array $batchContent
     * @return bool
     */
    public function sendBatch(array $batchContent)
    {
        if (empty($this->handle)) {
            return false;
        }

        $payload = $this->verifyPayload($batchContent);
        if (empty($payload)) {
            return false;
        }

        $currentBatchSize = count($batchContent);
        $this->logger->log(MappIntelligenceMessages::$WRITE_BATCH_DATA, $this->filename, $currentBatchSize);

        $payload .= "\n";

        fwrite($this->handle, $payload);

        return true;
    }
}
