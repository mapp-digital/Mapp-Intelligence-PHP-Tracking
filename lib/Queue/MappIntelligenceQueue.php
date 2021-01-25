<?php

require_once __DIR__ . '/MappIntelligenceEnrichment.php';
require_once __DIR__ . '/../MappIntelligenceMessages.php';
require_once __DIR__ . '/../MappIntelligenceParameter.php';
require_once __DIR__ . '/../Consumer/MappIntelligenceConsumerCurl.php';
require_once __DIR__ . '/../Consumer/MappIntelligenceConsumerFile.php';
require_once __DIR__ . '/../Consumer/MappIntelligenceConsumerFileRotation.php';
require_once __DIR__ . '/../Consumer/MappIntelligenceConsumerForkCurl.php';
require_once __DIR__ . '/../Consumer/MappIntelligenceConsumerType.php';

/**
 * Class MappIntelligenceQueue
 */
class MappIntelligenceQueue extends MappIntelligenceEnrichment
{
    /**
     * @var MappIntelligenceConsumer
     */
    private $consumer;
    /**
     * @var MappIntelligenceLogger
     */
    private $logger;
    /**
     * @var int
     */
    private $maxAttempt;
    /**
     * @var int
     */
    private $attemptTimeout;
    /**
     * @var int
     */
    private $maxBatchSize;
    /**
     * @var array
     */
    private $queue = array();

    /**
     * MappIntelligenceQueue constructor.
     * @param array $config
     */
    public function __construct($config = array())
    {
        parent::__construct($config);

        $consumerType = $config['consumerType'];
        $this->maxAttempt = $config['maxAttempt'];
        $this->attemptTimeout = $config['attemptTimeout'];
        $this->maxBatchSize = $config['maxBatchSize'];
        $this->logger = $config['logger'];
        $this->consumer = $config['consumer'];

        if (empty($this->consumer)) {
            if ($consumerType === MappIntelligenceConsumerType::CURL) {
                $this->consumer = new MappIntelligenceConsumerCurl($config);
            } elseif ($consumerType === MappIntelligenceConsumerType::FORK_CURL) {
                $this->consumer = new MappIntelligenceConsumerForkCurl($config);
            } elseif ($consumerType === MappIntelligenceConsumerType::FILE) {
                $this->consumer = new MappIntelligenceConsumerFile($config);
            } elseif ($consumerType === MappIntelligenceConsumerType::FILE_ROTATION) {
                $this->consumer = new MappIntelligenceConsumerFileRotation($config);
            }
        }
    }

    /**
     *
     */
    public function __destruct()
    {
        $this->flush();
    }

    /**
     * @param array $batchContent
     * @return bool
     */
    private function sendBatch(array $batchContent)
    {
        return $this->consumer->sendBatch($batchContent);
    }

    /**
     * @return bool
     */
    private function flushQueue()
    {
        $currentQueueSize = count($this->queue);
        $wasRequestSuccessful = true;
        $this->logger->log(MappIntelligenceMessages::$SENT_BATCH_REQUESTS, $currentQueueSize);

        while ($currentQueueSize > 0 && $wasRequestSuccessful) {
            $batchSize = min($this->maxBatchSize, $currentQueueSize);
            $batchContent = array_splice($this->queue, 0, $batchSize);
            $wasRequestSuccessful = $this->sendBatch($batchContent);

            if (!$wasRequestSuccessful) {
                $this->logger->log(MappIntelligenceMessages::$BATCH_REQUEST_FAILED);

                $this->queue = array_merge($batchContent, $this->queue);
            }

            $currentQueueSize = count($this->queue);
            $this->logger->log(MappIntelligenceMessages::$CURRENT_QUEUE_STATUS, $batchSize, $currentQueueSize);
        }

        if ($currentQueueSize === 0) {
            $this->logger->log(MappIntelligenceMessages::$QUEUE_IS_EMPTY);
        }

        return $wasRequestSuccessful;
    }

    /**
     * @param string $data
     * @return string
     */
    private function addRequestAsString($data)
    {
        $params = array();

        if (strpos($data, MappIntelligenceParameter::$USER_AGENT) === false) {
            $userAgent = $this->getUserAgent();
            if ($userAgent) {
                $params[MappIntelligenceParameter::$USER_AGENT] = $userAgent;
            }
        }

        if (strpos($data, MappIntelligenceParameter::$USER_IP) === false) {
            $userIP = $this->getRemoteAddress();
            if ($userIP) {
                $params[MappIntelligenceParameter::$USER_IP] = $userIP;
            }
        }

        $request = $data;
        $request .= (!empty($params) ? '&' . http_build_query($params, null, '&', PHP_QUERY_RFC3986) : '');
        $this->queue[] = $request;

        return $request;
    }

    /**
     * @param array $data
     * @return string
     */
    private function addRequestAsArray($data)
    {
        $request = '';
        $eid = $this->getEverId();
        if ($eid) {
            $eidArray = array(
                MappIntelligenceParameter::$EVER_ID => $eid
            );
            $data = array_merge($eidArray, $data);
        }

        $userAgent = $this->getUserAgent();
        if ($userAgent) {
            $data[MappIntelligenceParameter::$USER_AGENT] = $userAgent;
        }

        $userIP = $this->getRemoteAddress();
        if ($userIP) {
            $data[MappIntelligenceParameter::$USER_IP] = $userIP;
        }

        $requestURI = $this->getRequestURI();
        if ($requestURI) {
            $data[MappIntelligenceParameter::$PAGE_URL] = 'https://' . $requestURI;
        }

        $pageName = array_key_exists(MappIntelligenceParameter::$PAGE_NAME, $data)
            ? $data[MappIntelligenceParameter::$PAGE_NAME]
            : $this->getDefaultPageName();
        unset($data[MappIntelligenceParameter::$PAGE_NAME]);

        $request .= 'wt?p=' . $this->getMandatoryQueryParameter($pageName)
            . '&' . http_build_query($data, null, '&', PHP_QUERY_RFC3986);
        $this->queue[] = $request;

        return $request;
    }

    /**
     * @return string[]
     */
    public function getQueue()
    {
        return $this->queue;
    }

    /**
     * @param array|string $data
     */
    public function add($data = array())
    {
        if (is_string($data)) {
            $request = $this->addRequestAsString($data);
        } else {
            $request = $this->addRequestAsArray($data);
        }

        $currentQueueSize = count($this->queue);
        $this->logger->log(MappIntelligenceMessages::$ADD_THE_FOLLOWING_REQUEST_TO_QUEUE, $currentQueueSize, $request);

        if ($currentQueueSize >= $this->maxBatchSize) {
            $this->flush();
        }
    }

    /**
     * @return bool
     */
    public function flush()
    {
        $currentAttempt = 0;
        $wasRequestSuccessful = false;
        while (!$wasRequestSuccessful && $currentAttempt < $this->maxAttempt) {
            $wasRequestSuccessful = $this->flushQueue();
            $currentAttempt++;

            if (!$wasRequestSuccessful) {
                usleep($this->attemptTimeout * 1000);
            }
        }

        return $wasRequestSuccessful;
    }
}
