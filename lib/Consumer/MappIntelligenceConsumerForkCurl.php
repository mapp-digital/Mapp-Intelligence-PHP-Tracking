<?php

require_once __DIR__ . '/MappIntelligenceAbstractConsumer.php';

/**
 * Class MappIntelligenceConsumerForkCurl
 */
class MappIntelligenceConsumerForkCurl extends MappIntelligenceAbstractConsumer
{
    protected $type_ = 'fork-curl';

    /**
     * MappIntelligenceConsumerForkCurl constructor.
     * @param array $config
     */
    public function __construct(array $config = array())
    {
        parent::__construct($config);

        if (!function_exists('exec')) {
            // @codeCoverageIgnoreStart
            $this->log("The 'exec' function must be exist to use the consumer $this->type_");
            // @codeCoverageIgnoreEnd
        }

        $disableFunctions = explode(', ', ini_get('disable_functions'));
        $execEnabled = !in_array('exec', $disableFunctions);
        if (!$execEnabled) {
            // @codeCoverageIgnoreStart
            $this->log("The 'exec' function must be enabled to use the consumer $this->type_");
            // @codeCoverageIgnoreEnd
        }
    }

    /**
     * @param string $cmd
     * @param array $output
     * @param int $return_var
     */
    private function execute($cmd, &$output, &$return_var)
    {
        exec($cmd, $output, $return_var);
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

        // $payload = escapeshellarg($payload);

        $url = $this->getUrl();
        $currentBatchSize = count($batchContent);
        $this->log("Send batch data via forked cURL call to $url ($currentBatchSize req.)");

        $command = 'curl -X POST -H "Content-Type: text/plain"';
        $command .= ' -d "' . $payload . '"';
        $command .= ' -s -o /dev/null -w "%{http_code}"';
        $command .= ' "' . $url . '"';

        $this->log("Execute command: $command");
        $this->execute($command, $output, $return_var);

        $httpStatus = intval((count($output)) > 0 ? $output[0] : 0);
        $this->log("Batch request responding the status code $httpStatus");

        if ($httpStatus !== 200) {
            $this->log("[$httpStatus]: $return_var");
        }

        return $httpStatus === 200;
    }
}
