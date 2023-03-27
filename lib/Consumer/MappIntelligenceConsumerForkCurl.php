<?php

require_once __DIR__ . '/MappIntelligenceAbstractConsumer.php';
require_once __DIR__ . '/MappIntelligenceConsumerType.php';
require_once __DIR__ . '/../MappIntelligenceMessages.php';

/**
 * Class MappIntelligenceConsumerForkCurl
 */
class MappIntelligenceConsumerForkCurl extends MappIntelligenceAbstractConsumer
{
    protected $type = MappIntelligenceConsumerType::FORK_CURL;

    /**
     * MappIntelligenceConsumerForkCurl constructor.
     * @param array $config
     */
    public function __construct($config = array())
    {
        parent::__construct($config);

        // @codeCoverageIgnoreStart
        if (!function_exists('exec')) {
            $this->logger->error(MappIntelligenceMessages::$EXEC_MUST_BE_EXIST, $this->type);
        }
        // @codeCoverageIgnoreEnd

        $disableFunctions = explode(', ', ini_get('disable_functions'));
        $execEnabled = !in_array('exec', $disableFunctions);
        // @codeCoverageIgnoreStart
        if (!$execEnabled) {
            $this->logger->error(MappIntelligenceMessages::$EXEC_MUST_BE_ENABLED, $this->type);
        }
        // @codeCoverageIgnoreEnd
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

        $url = $this->getUrl();
        $currentBatchSize = count($batchContent);
        $this->logger->debug(MappIntelligenceMessages::$SEND_BATCH_DATA, $url, $currentBatchSize);

        $command = 'curl -X POST -H "Content-Type: text/plain"';
        $command .= ' -d "' . $payload . '"';
        $command .= ' -s -o /dev/null -w "%{http_code}"';
        $command .= ' "' . $url . '"';

        $this->logger->debug(MappIntelligenceMessages::$EXECUTE_COMMAND, $command);
        $this->execute($command, $output, $return_var);

        $httpStatus = intval((count($output)) > 0 ? $output[0] : 0);
        $this->logger->debug(MappIntelligenceMessages::$BATCH_REQUEST_STATUS, $httpStatus);

        if ($httpStatus !== 200) {
            $this->logger->warn(MappIntelligenceMessages::$BATCH_RESPONSE_TEXT, $httpStatus, $return_var);
        }

        return $httpStatus === 200;
    }
}
