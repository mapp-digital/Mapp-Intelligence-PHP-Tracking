<?php

require_once __DIR__ . '/Config/MappIntelligenceConfig.php';
require_once __DIR__ . '/Queue/MappIntelligenceQueue.php';
require_once __DIR__ . '/Data/MappIntelligenceBasic.php';
require_once __DIR__ . '/Data/MappIntelligenceAction.php';
require_once __DIR__ . '/Data/MappIntelligenceCustomer.php';
require_once __DIR__ . '/Data/MappIntelligenceCampaign.php';
require_once __DIR__ . '/Data/MappIntelligenceOrder.php';
require_once __DIR__ . '/Data/MappIntelligencePage.php';
require_once __DIR__ . '/Data/MappIntelligenceProduct.php';
require_once __DIR__ . '/Data/MappIntelligenceSession.php';

/**
 * Class Tracking
 * @package MappIntelligence
 */
class MappIntelligence extends MappIntelligenceConfig
{
    const V4 = 'v4';
    const V5 = 'v5';
    const SMART = 'smart';
    const CLIENT_SIDE_COOKIE = '1';
    const SERVER_SIDE_COOKIE = '3';

    /**
     * @var MappIntelligence
     */
    private static $instance_;

    /**
     * @var MappIntelligenceQueue
     */
    private $queue_;

    /**
     * Tracking constructor.
     * @param array $config
     */
    protected function __construct($config = array())
    {
        parent::__construct($config);

        $this->queue_ = new MappIntelligenceQueue($this->config_);
    }

    /**
     * @param array $config
     * @return MappIntelligence
     */
    public static function getInstance($config = array())
    {
        if (!isset(self::$instance_)) {
            self::$instance_ = new MappIntelligence($config);
        }

        return self::$instance_;
    }

    /**
     * @param int $maxLength
     * @return string[]
     */
    private function simulateEmptyValues($maxLength)
    {
        $emptyArray = array();
        for ($i = 0; $i < $maxLength; $i++) {
            $emptyArray[] = '';
        }

        return $emptyArray;
    }

    /**
     * @param mixed $value
     * @return string
     */
    private function convertToString($value)
    {
        if (is_bool($value)) {
            $value = (($value) ? '1' : '0');
        }

        return $value;
    }

    /**
     * @param array $productInformation
     * @return array
     */
    private function mergeProducts($productInformation)
    {
        $requestInformation = array();
        $length = count($productInformation);

        for ($i = 0; $i < $length; $i++) {
            foreach ($productInformation[$i] as $key => $product) {
                if (!array_key_exists($key, $requestInformation)) {
                    $requestInformation[$key] = $this->simulateEmptyValues($length);
                }

                $requestInformation[$key][$i] = $this->convertToString($product);

                if ($i === $length - 1) {
                    $requestInformation[$key] = implode(';', $requestInformation[$key]);
                }
            }
        }

        return $requestInformation;
    }

    /**
     * @param mixed $data
     *
     * @return bool
     */
    private function isDataObject($data)
    {
        return $data instanceof MappIntelligenceBasic;
    }

    /**
     * @param string $pixelVersion
     * @param string $context
     *
     * @return bool
     */
    public function setUserId($pixelVersion, $context)
    {
        if (!$this->config_['trackId'] || !$this->config_['trackDomain']) {
            $this->log('The Mapp Intelligence "trackDomain" and "trackId" are required to set user cookie');
            return false;
        }

        $this->queue_->setUserId($pixelVersion, $context);
        return true;
    }

    /**
     * @return bool
     */
    public function flush()
    {
        return $this->queue_->flush();
    }

    /**
     * @param array $data
     *
     * @return bool
     */
    public function track($data = array())
    {
        if ($this->config_['deactivate']) {
            $this->log('Mapp Intelligence tracking is deactivated');
            return false;
        }

        if (!$this->config_['trackId'] || !$this->config_['trackDomain']) {
            $this->log('The Mapp Intelligence "trackDomain" and "trackId" are required to track data');
            return false;
        }

        if (is_array($data)) {
            $requestData = array();
            foreach ($data as $key => $value) {
                if ($this->isDataObject($value) || is_array($value)) {
                    if (is_array($value) && count($value) > 0) {
                        $products = array();
                        for ($i = 0; $i < count($value); $i++) {
                            $products[] = $value[$i]->getQueryParameter();
                        }

                        $requestData = array_merge($requestData, $this->mergeProducts($products));
                    } else {
                        $requestData = array_merge($requestData, $value->getQueryParameter());
                    }
                } else {
                    $requestData[$key] = $value;
                }
            }

            if (count($requestData) > 0) {
                $this->queue_->add($requestData);

                return true;
            }
        }

        return false;
    }
}
