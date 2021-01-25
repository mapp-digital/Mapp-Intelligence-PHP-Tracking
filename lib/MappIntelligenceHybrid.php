<?php

// @codeCoverageIgnoreStart
require_once __DIR__ . '/Config/MappIntelligenceConfig.php';
require_once __DIR__ . '/Config/MappIntelligenceProperties.php';
require_once __DIR__ . '/Queue/MappIntelligenceQueue.php';
// @codeCoverageIgnoreEnd

class MappIntelligenceHybrid
{
    const PIXEL = 'R0lGODlhAQABAIAAAP///wAAACH5BAEAAAAALAAAAAABAAEAAAICRAEAOw==';
    const CONTENT_TYPE = 'Content-Type: image/gif';
    const CONTENT_LENGTH = 'Content-Length: 43';

    /**
     * Mapp Intelligence config map
     */
    protected $cfg;
    /**
     * Deactivate the tracking functionality.
     */
    private $deactivate;
    /**
     * Deactivate the tracking functionality.
     */
    private $requestURL;

    /**
     * MappIntelligenceCLICronjob constructor.
     * @param MappIntelligenceConfig|array $config
     */
    public function __construct($config = array())
    {
        $mappIntelligenceConfig = new MappIntelligenceConfig($config);
        $this->cfg = $mappIntelligenceConfig->build();

        $this->deactivate = $this->cfg[MappIntelligenceProperties::DEACTIVATE];
        $this->requestURL = $this->cfg[MappIntelligenceProperties::REQUEST_URL];

        $consumerType = $this->cfg[MappIntelligenceProperties::CONSUMER_TYPE];
        if ($consumerType !== MappIntelligenceConsumerType::FILE_ROTATION
            && $consumerType !== MappIntelligenceConsumerType::CUSTOM) {
            $this->cfg[MappIntelligenceProperties::CONSUMER_TYPE] = MappIntelligenceConsumerType::FILE;
        }
    }

    /**
     * @return string
     */
    private function getQueryString()
    {
        if (!empty($this->requestURL)) {
            return array_key_exists('query', $this->requestURL) ? $this->requestURL['query'] : '';
        }

        return array_key_exists('QUERY_STRING', $_SERVER) ? $_SERVER['QUERY_STRING'] : '';
    }

    /**
     * @return string
     */
    public function getResponseAsBase64()
    {
        return self::PIXEL;
    }

    /**
     * @return string
     */
    public function getResponseAsImage()
    {
        return base64_decode($this->getResponseAsBase64());
    }

    /**
     * @param string $rURL HTTP request URL
     */
    public function setRequestURL($rURL)
    {
        if (filter_var($rURL, FILTER_VALIDATE_URL)) {
            $this->requestURL = parse_url($rURL);
        }
    }

    /**
     * @param boolean $withoutResponse
     */
    public function run($withoutResponse = false)
    {
        $queryString = $this->getQueryString();
        if (!$this->deactivate && !empty($queryString)) {
            $config = new MappIntelligenceConfig($this->cfg);
            $queue = new MappIntelligenceQueue($config->build());
            $queue->add('wt?' . $queryString);
            $queue->flush();
        }

        if (!$withoutResponse) {
            header(self::CONTENT_TYPE);
            header(self::CONTENT_LENGTH);

            echo $this->getResponseAsImage();
        }
    }
}
