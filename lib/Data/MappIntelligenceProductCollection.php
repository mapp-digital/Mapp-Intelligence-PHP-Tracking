<?php

/**
 * Class MappIntelligenceProductCollection
 */
class MappIntelligenceProductCollection
{
    /**
     * List of products.
     */
    private $data = array();

    /**
     * @param object $value Add value to map
     *
     * @return $this
     */
    public function add($value)
    {
        $this->data[] = $value;
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
