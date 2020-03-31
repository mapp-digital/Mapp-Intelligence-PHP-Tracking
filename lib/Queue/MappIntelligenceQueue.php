<?php

require_once __DIR__ . '/MappIntelligenceEnrichment.php';
require_once __DIR__ . '/../Consumer/MappIntelligenceConsumerCurl.php';
require_once __DIR__ . '/../Consumer/MappIntelligenceConsumerFile.php';
require_once __DIR__ . '/../Consumer/MappIntelligenceConsumerForkCurl.php';

/**
 * Class MappIntelligenceQueue
 */
class MappIntelligenceQueue extends MappIntelligenceEnrichment
{
    /**
     * @var MappIntelligenceConsumerCurl|MappIntelligenceConsumerFile|MappIntelligenceConsumerForkCurl
     */
    private $consumer_;
    /**
     * @var array
     */
    private $queue_ = array();
    /**
     * @var array
     */
    private $consumerTypes_ = array(
        'curl' => 'MappIntelligenceConsumerCurl',
        'fork-curl' => 'MappIntelligenceConsumerForkCurl',
        'file' => 'MappIntelligenceConsumerFile'
    );

    /**
     * MappIntelligenceQueue constructor.
     * @param array $config
     */
    public function __construct($config = array())
    {
        parent::__construct($config);

        $Consumer = $this->consumerTypes_[$this->config_['consumer']];
        $this->consumer_ = new $Consumer($this->config_);
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
        return $this->consumer_->sendBatch($batchContent);
    }

    /**
     * @return bool
     */
    private function flushQueue()
    {
        $currentQueueSize = count($this->queue_);
        $wasRequestSuccessful = true;
        $this->log("Sent batch requests, current queue size is $currentQueueSize req.");

        while ($currentQueueSize > 0 && $wasRequestSuccessful) {
            $batchSize = min($this->config_['maxBatchSize'], $currentQueueSize);
            $batchContent = array_splice($this->queue_, 0, $batchSize);
            $wasRequestSuccessful = $this->sendBatch($batchContent);

            if (!$wasRequestSuccessful) {
                $this->log('Batch request failed!');

                $this->queue_ = array_merge($batchContent, $this->queue_);
            }

            $currentQueueSize = count($this->queue_);
            $this->log("Batch of $batchSize req. sent, current queue size is $currentQueueSize req.");
        }

        if ($currentQueueSize === 0) {
            $this->log('MappIntelligenceQueue is empty');
        }

        return $wasRequestSuccessful;
    }

    /**
     * @return string[]
     */
    public function getQueue()
    {
        return $this->queue_;
    }

    /**
     * @param array|string $data
     */
    public function add($data = array())
    {
        $request = '';

        if (is_string($data)) {
            $params = array();

            if (strpos($data, 'X-WT-UA') === false) {
                $userAgent = $this->getUserAgent();
                if ($userAgent) {
                    $params['X-WT-UA'] = $userAgent;
                }
            }

            if (strpos($data, 'X-WT-IP') === false) {
                $userIP = $this->getUserIP();
                if ($userIP) {
                    $params['X-WT-IP'] = $userIP;
                }
            }

            $request .= $data;
            $request .= (count($params) > 0 ? '&' . http_build_query($params, null, '&', PHP_QUERY_RFC3986) : '');
            $this->queue_[] = $request;
        } else {
            $eid = $this->getEverId();
            if ($eid) {
                $data = array_merge(array(
                    'eid' => $eid
                ), $data);
            }

            $userAgent = $this->getUserAgent();
            if ($userAgent) {
                $data['X-WT-UA'] = $userAgent;
            }

            $userIP = $this->getUserIP();
            if ($userIP) {
                $data['X-WT-IP'] = $userIP;
            }

            $requestURI = $this->getRequestURI();
            if ($requestURI) {
                $data['pu'] = 'https://' . $requestURI;
            }

            $pageName = array_key_exists('pn', $data) ? $data['pn'] : $this->getDefaultPageName();
            unset($data['pn']);

            $request .= 'wt?p=' . $this->getMandatoryQueryParameter($pageName)
                . '&' . http_build_query($data, null, '&', PHP_QUERY_RFC3986);
            $this->queue_[] = $request;
        }

        $currentQueueSize = count($this->queue_);
        $this->log("Add the following request to queue ($currentQueueSize req.): $request");

        if ($currentQueueSize >= $this->config_['maxBatchSize']) {
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
        while (!$wasRequestSuccessful && $currentAttempt < $this->config_['maxAttempt']) {
            $wasRequestSuccessful = $this->flushQueue();
            $currentAttempt++;

            if (!$wasRequestSuccessful) {
                usleep($this->config_['attemptTimeout'] * 1000);
            }
        }

        return $wasRequestSuccessful;
    }
}
