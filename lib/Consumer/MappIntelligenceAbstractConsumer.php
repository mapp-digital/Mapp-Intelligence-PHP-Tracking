<?php

require_once __DIR__ . '/../Config/MappIntelligenceConfig.php';

/**
 * Class MappIntelligenceAbstractConsumer
 */
abstract class MappIntelligenceAbstractConsumer extends MappIntelligenceConfig
{
    private $maxPayloadSize_;
    private $maxBatchSize_;
    protected $type_ = '';

    /**
     * MappIntelligenceAbstractConsumer constructor.
     * @param array $config
     */
    protected function __construct(array $config = array())
    {
        parent::__construct($config);

        $this->maxPayloadSize_ = 24 * 1024 * 1024; // max. 24MB
        $this->maxBatchSize_ = 10 * 1000; // max. 10k req. per batch
    }

    /**
     * @return string
     */
    final protected function getUrl()
    {
        $url = (($this->config_['forceSSL']) ? 'https://' : 'http://');
        $url .= $this->config_['trackDomain'];
        $url .= '/' . $this->config_['trackId'] . '/batch';

        return $url;
    }

    /**
     * @param array $batchContent
     * @return bool|string
     */
    final protected function verifyPayload(array $batchContent)
    {
        $currentBatchSize = count($batchContent);
        if ($currentBatchSize > $this->maxBatchSize_) {
            $this->log("Batch size is larger than $this->maxBatchSize_ req. ($currentBatchSize req.)");
            return false;
        }

        $glue = "\n";
        if ($this->type_ === 'fork-curl') {
            $glue = '\n';
        }

        $payload = implode($glue, $batchContent);
        if (strlen($payload) >= $this->maxPayloadSize_) {
            $currentPayloadSize = round(strlen($payload) / 1024 / 1024, 2);
            $this->log('Payload size is larger than 24MB (' . $currentPayloadSize . 'MB)');
            return false;
        }

        return $payload;
    }

    /**
     * @param array $batchContent
     * @return bool
     */
    abstract public function sendBatch(array $batchContent);
}
