<?php

require_once __DIR__ . '/../Consumer/MappIntelligenceConsumerType.php';

/**
 * Class MappIntelligenceConfigProperties
 */
class MappIntelligenceConfigProperties
{
    /**
     * Properties object.
     */
    private $prop = array();

    /**
     * @param string $propertyFile Property file path
     */
    public function __construct($propertyFile)
    {
        if (file_exists($propertyFile)) {
            $this->prop = parse_ini_file($propertyFile);
        }
    }

    /**
     * @param string $propertyName Name of the property
     *
     * @return string
     */
    private function getProperty($propertyName)
    {
        return array_key_exists($propertyName, $this->prop) ? $this->prop[$propertyName] : null;
    }

    /**
     * @param string $propertyName Name of the property
     * @param string $defaultValue Default value for the property
     *
     * @return string
     */
    public function getStringProperty($propertyName, $defaultValue)
    {
        $propertyValue = $this->getProperty($propertyName);

        return ((!is_null($propertyValue)) ? $propertyValue : $defaultValue);
    }

    /**
     * @param string $propertyName Name of the property
     * @param bool $defaultValue Default value for the property
     *
     * @return boolean
     */
    public function getBooleanProperty($propertyName, $defaultValue)
    {
        $propertyValue = $this->getProperty($propertyName);

        return ((!is_null($propertyValue)) ? $propertyValue === 'true' || $propertyValue === '1' : $defaultValue);
    }

    /**
     * @param string $propertyName Name of the property
     * @param int $defaultValue Default value for the property
     *
     * @return int
     */
    public function getIntegerProperty($propertyName, $defaultValue)
    {
        $propertyValue = $this->getProperty($propertyName);
        $propertyValue = ((!is_null($propertyValue)) ? $propertyValue : '');

        return ((preg_match("/^\\d+$/", $propertyValue)) ? intval($propertyValue) : $defaultValue);
    }

    /**
     * @param string $propertyName Name of the property
     * @param string $defaultValue Default value for the property
     *
     * @return string
     */
    public function getConsumerTypeProperty($propertyName, $defaultValue)
    {
        $consumerValue = $defaultValue;
        $propertyValue = $this->getProperty($propertyName);

        switch ($propertyValue) {
            case MappIntelligenceConsumerType::FILE:
                $consumerValue = MappIntelligenceConsumerType::FILE;
                break;
            case MappIntelligenceConsumerType::CURL:
                $consumerValue = MappIntelligenceConsumerType::CURL;
                break;
            case MappIntelligenceConsumerType::FORK_CURL:
                $consumerValue = MappIntelligenceConsumerType::FORK_CURL;
                break;
            case MappIntelligenceConsumerType::FILE_ROTATION:
                $consumerValue = MappIntelligenceConsumerType::FILE_ROTATION;
                break;
            case MappIntelligenceConsumerType::CUSTOM:
                $consumerValue = MappIntelligenceConsumerType::CUSTOM;
                break;
            default:
                break;
        }

        return $consumerValue;
    }

    /**
     * @param string $propertyName Name of the property
     * @param array $defaultValue Default value for the property
     *
     * @return array
     */
    public function getListProperty($propertyName, $defaultValue)
    {
        $listValue = $defaultValue;
        $propertyValue = $this->getProperty($propertyName);

        if (!is_null($propertyValue)) {
            $listValue = $propertyValue;
        }

        return $listValue;
    }
}
