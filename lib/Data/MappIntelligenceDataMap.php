<?php

/**
 * Class MappIntelligenceDataMap
 */
class MappIntelligenceDataMap
{
    /**
     * Tracking data.
     */
    private $data = array();

    /**
     * @param object $value Mapp Intelligence action data
     * @return $this
     */
    public function action($value)
    {
        $this->data['action'] = $value;
        return $this;
    }

    /**
     * @param object $value Mapp Intelligence campaign data
     * @return $this
     */
    public function campaign($value)
    {
        $this->data['campaign'] = $value;
        return $this;
    }

    /**
     * @param object $value Mapp Intelligence customer data
     * @return $this
     */
    public function customer($value)
    {
        $this->data['customer'] = $value;
        return $this;
    }

    /**
     * @param object $value Mapp Intelligence order data
     * @return $this
     */
    public function order($value)
    {
        $this->data['order'] = $value;
        return $this;
    }

    /**
     * @param object $value Mapp Intelligence page data
     * @return $this
     */
    public function page($value)
    {
        $this->data['page'] = $value;
        return $this;
    }

    /**
     * @param object $value Mapp Intelligence product data
     * @return $this
     */
    public function product($value)
    {
        $this->data['product'] = $value;
        return $this;
    }

    /**
     * @param object $value Mapp Intelligence session data
     * @return $this
     */
    public function session($value)
    {
        $this->data['session'] = $value;
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
