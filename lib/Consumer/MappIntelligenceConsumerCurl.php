<?php

require_once __DIR__ . '/MappIntelligenceAbstractConsumer.php';
require_once __DIR__ . '/MappIntelligenceConsumerType.php';
require_once __DIR__ . '/../MappIntelligenceMessages.php';

/**
 * Class MappIntelligenceConsumerCurl
 */
class MappIntelligenceConsumerCurl extends MappIntelligenceAbstractConsumer
{
    protected $type = MappIntelligenceConsumerType::CURL;

    /**
     * MappIntelligenceConsumerCurl constructor.
     * @param array $config
     */
    public function __construct($config = array())
    {
        parent::__construct($config);

        // @codeCoverageIgnoreStart
        if (!function_exists('curl_init')) {
            $this->logger->error(MappIntelligenceMessages::$CURL_PHP_EXTENSION_IS_REQUIRED, $this->type);
        }
        // @codeCoverageIgnoreEnd
    }

    /**
     * @param array $batchContent
     * @return bool
     */
    public function sendBatch(array $batchContent)
    {
        $payload = $this->verifyPayload($batchContent);
        if (!$payload) {
            return false;
        }

        $url = $this->getUrl();
        $currentBatchSize = count($batchContent);
        $this->logger->debug(MappIntelligenceMessages::$SEND_BATCH_DATA, $url, $currentBatchSize);

        // ToDo: support "Content-Encoding: gzip"

        $s = curl_init();

        curl_setopt($s, CURLOPT_URL, $url);
        curl_setopt($s, CURLOPT_HTTPHEADER, array('Content-Type: text/plain'));
        curl_setopt($s, CURLOPT_POST, true);
        curl_setopt($s, CURLOPT_POSTFIELDS, $payload);
        curl_setopt($s, CURLOPT_RETURNTRANSFER, true);

         // The maximum number of seconds to allow cURL functions to execute
        curl_setopt($s, CURLOPT_TIMEOUT, 30);
        // The number of seconds to wait while trying to connect
        curl_setopt($s, CURLOPT_CONNECTTIMEOUT, 5);

        curl_exec($s);
        $httpStatus = curl_getinfo($s, CURLINFO_HTTP_CODE);

        $this->logger->debug(MappIntelligenceMessages::$BATCH_REQUEST_STATUS, $httpStatus);

        if ($httpStatus !== 200) {
            $errno = curl_errno($s);
            $error = curl_error($s);
            $this->logger->warn(MappIntelligenceMessages::$BATCH_RESPONSE_TEXT, $errno, $error);

            curl_close($s);
            return false;
        }

        curl_close($s);
        return true;
    }
}
