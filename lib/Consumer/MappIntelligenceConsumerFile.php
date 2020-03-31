<?php

require_once __DIR__ . '/MappIntelligenceAbstractConsumer.php';

/**
 * Class MappIntelligenceConsumerFile
 */
class MappIntelligenceConsumerFile extends MappIntelligenceAbstractConsumer
{
    protected $type_ = 'file';
    private $handle_;

    /**
     * MappIntelligenceConsumerFile constructor.
     * @param array $config
     */
    public function __construct(array $config = array())
    {
        parent::__construct($config);

        try {
            $this->handle_ = fopen($this->config_['filename'], $this->config_['fileMode']);
        } catch (Exception $e) {
            $this->log($e->getMessage());
        }
    }

    /**
     *
     */
    public function __destruct()
    {
        if ($this->handle_) {
            fclose($this->handle_);
        }
    }

    /**
     * @param array $batchContent
     * @return bool
     */
    public function sendBatch(array $batchContent)
    {
        if (!$this->handle_) {
            return false;
        }

        $payload = $this->verifyPayload($batchContent);
        if (!$payload) {
            return false;
        }

        $currentBatchSize = count($batchContent);
        $this->log("Write batch data in " . $this->config_['filename'] . " ($currentBatchSize req.)");

        $payload .= "\n";

        fwrite($this->handle_, $payload);

        return true;
    }
}
