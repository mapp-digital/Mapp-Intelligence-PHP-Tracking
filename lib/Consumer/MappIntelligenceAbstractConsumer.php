<?php

require_once __DIR__ . '/MappIntelligenceConsumerType.php';
require_once __DIR__ . '/../MappIntelligenceConsumer.php';
require_once __DIR__ . '/../MappIntelligenceMessages.php';

/**
 * Class MappIntelligenceAbstractConsumer
 */
abstract class MappIntelligenceAbstractConsumer implements MappIntelligenceConsumer
{
    /**
     * Constant for max payload size.
     */
    const MAX_PAYLOAD_SIZE = 24 * 1024 * 1024;
    /**
     * Constant for max batch size.
     */
    const MAX_BATCH_SIZE = 10 * 1000;

    /**
     * @var MappIntelligenceDebugLogger
     */
    protected $logger;
    /**
     * @var string
     */
    protected $type = '';

    private $trackId;
    private $trackDomain;
    private $forceSSL;

    /**
     * MappIntelligenceAbstractConsumer constructor.
     * @param array $config
     */
    protected function __construct($config = array())
    {
        $this->trackId = $config['trackId'];
        $this->trackDomain = $config['trackDomain'];
        $this->forceSSL = $config['forceSSL'];
        $this->logger = $config['logger'];
    }

    /**
     * @return string
     */
    final protected function getUrl()
    {
        return (($this->forceSSL) ? 'https://' : 'http://')
            . $this->trackDomain . '/'
            . $this->trackId . '/batch';
    }

    /**
     * @param array $batchContent
     * @return bool|string
     */
    final protected function verifyPayload(array $batchContent)
    {
        $currentBatchSize = count($batchContent);
        if ($currentBatchSize > self::MAX_BATCH_SIZE) {
            $this->logger->error(
                MappIntelligenceMessages::$TO_LARGE_BATCH_SIZE,
                self::MAX_BATCH_SIZE,
                $currentBatchSize
            );
            return false;
        }

        $glue = "\n";
        if ($this->type === MappIntelligenceConsumerType::FORK_CURL) {
            $glue = '\n';
        }

        $payload = implode($glue, $batchContent);
        if (strlen($payload) >= self::MAX_PAYLOAD_SIZE) {
            $currentPayloadSize = round(strlen($payload) / 1024 / 1024, 2);
            $this->logger->error(MappIntelligenceMessages::$TO_LARGE_PAYLOAD_SIZE, $currentPayloadSize);
            return false;
        }

        return $payload;
    }
}
