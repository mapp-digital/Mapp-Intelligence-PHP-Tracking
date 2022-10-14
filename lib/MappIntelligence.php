<?php

require_once __DIR__ . '/MappIntelligenceParameter.php';
require_once __DIR__ . '/MappIntelligenceVersion.php';
require_once __DIR__ . '/MappIntelligenceMessages.php';

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
require_once __DIR__ . '/Data/MappIntelligenceProductCollection.php';

/**
 * Class Tracking
 * @package MappIntelligence
 */
class MappIntelligence
{
    const V4 = 'v4';
    const V5 = 'v5';
    const SMART = 'smart';
    const CLIENT_SIDE_COOKIE = '1';
    const SERVER_SIDE_COOKIE = '3';
    const TRACKING_PLATFORM = 'PHP';

    /**
     * @var MappIntelligence
     */
    private static $instance;
    /**
     * @var MappIntelligenceQueue
     */
    private $queue;
    /**
     * @var MappIntelligenceLogger
     */
    private $logger;
    /**
     * @var string
     */
    private $trackId;
    /**
     * @var string
     */
    private $trackDomain;
    /**
     * @var bool
     */
    private $deactivate;
    /**
     * @var int
     */
    private $statistics;

    /**
     * Tracking constructor.
     * @param mixed $config
     */
    private function __construct($config = array())
    {
        $mappIntelligenceConfig = new MappIntelligenceConfig($config);
        $cfg = $mappIntelligenceConfig->build();

        $this->trackId = $cfg['trackId'];
        $this->trackDomain = $cfg['trackDomain'];
        $this->logger = $cfg['logger'];
        $this->deactivate = $cfg['deactivate'];
        $this->statistics = $cfg['statistics'];

        $this->queue = new MappIntelligenceQueue($cfg);
    }

    /**
     * @param mixed $config
     * @return MappIntelligence
     */
    public static function getInstance($config = array())
    {
        if (!isset(self::$instance)) {
            self::$instance = new MappIntelligence($config);
        }

        return self::$instance;
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
     * @param mixed $data Object to check
     *
     * @return boolean
     */
    private function isProductCollection($data)
    {
        return $data instanceof MappIntelligenceProductCollection;
    }

    /**
     * @return bool
     */
    private function isTrackable()
    {
        if ($this->deactivate) {
            $this->logger->log(MappIntelligenceMessages::$TRACKING_IS_DEACTIVATED);
            return false;
        }

        if (!$this->trackId || !$this->trackDomain) {
            $this->logger->log(MappIntelligenceMessages::$REQUIRED_TRACK_ID_AND_DOMAIN_FOR_TRACKING);
            return false;
        }

        return true;
    }

    /**
     * @param mixed $data
     *
     * @return bool
     */
    private function isDataValid($data)
    {
        return (is_array($data) || method_exists($data, 'build'));
    }

    /**
     * @param array $requestData Tracking request data
     *
     * @return bool
     */
    private function addToRequestQueue($requestData)
    {
        $requestData[MappIntelligenceParameter::$PIXEL_FEATURES] = $this->statistics;
        $requestData[MappIntelligenceParameter::$VERSION] = MappIntelligenceVersion::get();
        $requestData[MappIntelligenceParameter::$TRACKING_PLATFORM] = self::TRACKING_PLATFORM;

        $this->queue->add($requestData);
        return true;
    }

    /**
     * @param mixed $data
     *
     * @return array
     */
    private function convertDataToArray($data)
    {
        $tmpData = array();

        try {
            if (is_array($data)) {
                $tmpData = $data;
            } elseif (method_exists($data, 'build')) {
                $tmpData = $data->build();
            }
        } catch (Exception $e) {
            // do nothing
        }

        return $tmpData;
    }

    /**
     * @param mixed $data
     *
     * @return array
     */
    private function getRequestData($data)
    {
        $data = $this->convertDataToArray($data);
        $requestData = array();

        foreach ($data as $key => $value) {
            if ($this->isDataObject($value) || $this->isProductCollection($value) || is_array($value)) {
                if ((is_array($value) && !empty($value)) || $this->isProductCollection($value)) {
                    $value = $this->convertDataToArray($value);
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

        return $requestData;
    }

    /**
     * @param mixed $data
     *
     * @return bool
     */
    public function track($data = array())
    {
        if ($this->isTrackable() && $data != null && $this->isDataValid($data)) {
            return $this->addToRequestQueue($this->getRequestData($data));
        }

        return false;
    }

    /**
     * @param string $pixelVersion
     * @param string $context
     *
     * @return bool
     */
    public function setUserId($pixelVersion, $context)
    {
        if (!$this->trackId || !$this->trackDomain) {
            $this->logger->log(MappIntelligenceMessages::$REQUIRED_TRACK_ID_AND_DOMAIN_FOR_COOKIE);
            return false;
        }

        $this->queue->setUserId($pixelVersion, $context);
        return true;
    }

    /**
     * @param string $pixelVersion
     * @param string $context
     *
     * @return MappIntelligenceCookie|null
     */
    public function getUserIdCookie($pixelVersion, $context)
    {
        if (!$this->trackId || !$this->trackDomain) {
            $this->logger->log(MappIntelligenceMessages::$REQUIRED_TRACK_ID_AND_DOMAIN_FOR_COOKIE);
            return null;
        }

        return $this->queue->getUserIdCookie($pixelVersion, $context);
    }

    /**
     * @return bool
     */
    public function flush()
    {
        return $this->queue->flush();
    }
}
