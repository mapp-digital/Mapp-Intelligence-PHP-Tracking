<?php

require_once __DIR__ . '/MappIntelligenceAbstractConsumer.php';

/**
 * Class MappIntelligenceConsumerCurl
 */
class MappIntelligenceConsumerCurl extends MappIntelligenceAbstractConsumer
{
    protected $type_ = 'curl';

    /**
     * MappIntelligenceConsumerCurl constructor.
     * @param array $config
     */
    public function __construct(array $config = array())
    {
        parent::__construct($config);

        if (!function_exists('curl_init')) {
            // @codeCoverageIgnoreStart
            $this->log("The cURL PHP extension is required to use the consumer $this->type_");
            // @codeCoverageIgnoreEnd
        }
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
        $this->log("Send batch data via cURL call to $url ($currentBatchSize req.)");

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

        $this->log("Batch request responding the status code $httpStatus");

        if ($httpStatus !== 200) {
            $errno = curl_errno($s);
            $error = curl_error($s);
            $this->log("[$errno]: $error");

            curl_close($s);
            return false;
        }

        curl_close($s);
        return true;
    }
}
