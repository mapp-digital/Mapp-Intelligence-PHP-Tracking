<?php

/**
 * Class MappIntelligenceParameterMap
 */
class MappIntelligenceParameterMap
{
    /**
     * Tracking data.
     */
    private $data = array();

    /**
     * @param string $key Add key to map
     * @param string $value Add value to map
     *
     * @return $this
     */
    public function add($key, $value)
    {
        $this->data[$key] = $value;
        return $this;
    }

    /**
     * @return array
     */
    public function build()
    {
        return $this->data;
    }
}
