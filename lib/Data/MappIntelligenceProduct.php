<?php

require_once __DIR__ . '/MappIntelligenceBasic.php';

/**
 * Class MappIntelligenceProduct
 */
class MappIntelligenceProduct extends MappIntelligenceBasic
{
    const VIEW = 'view';
    const BASKET = 'add';
    const CONFIRMATION = 'conf';

    protected $id = '';
    protected $cost = 0;
    protected $quantity = 0;
    protected $status = 'view';
    protected $variant = '';
    protected $soldOut = false;
    protected $parameter = array();
    protected $category = array();

    /**
     * MappIntelligenceProduct constructor.
     * @param string $id
     */
    public function __construct($id = '')
    {
        $this->id = $id;
        $this->filterQueryParameter = false;
    }

    /**
     * @return array
     */
    protected function getQueryList()
    {
        return array(
            'id' => 'ba',
            'cost' => 'co',
            'quantity' => 'qn',
            'status' => 'st',
            'variant' => 'cb767',
            'soldOut' => 'cb760',
            'parameter' => 'cb',
            'category' => 'ca'
        );
    }

    /**
     * @param string $id
     *
     * @return $this
     */
    public function setId($id = '')
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @param float $cost
     *
     * @return $this
     */
    public function setCost($cost)
    {
        $this->cost = $cost;

        return $this;
    }

    /**
     * @param int $quantity
     *
     * @return $this
     */
    public function setQuantity($quantity)
    {
        $this->quantity = $quantity;

        return $this;
    }

    /**
     * @param string $status
     *
     * @return $this
     */
    public function setStatus($status)
    {
        if ($status === self::VIEW || $status === self::BASKET || $status === self::CONFIRMATION) {
            $this->status = $status;
        }

        return $this;
    }

    /**
     * @param string $variant
     *
     * @return $this
     */
    public function setVariant($variant)
    {
        $this->variant = $variant;

        return $this;
    }

    /**
     * @param bool $soldOut
     *
     * @return $this
     */
    public function setSoldOut($soldOut)
    {
        $this->soldOut = $soldOut;

        return $this;
    }

    /**
     * @param int $id
     * @param string $value
     *
     * @return $this
     */
    public function setParameter($id, $value)
    {
        $this->parameter[$id] = $value;

        return $this;
    }

    /**
     * @param int $id
     * @param string $value
     *
     * @return $this
     */
    public function setCategory($id, $value)
    {
        $this->category[$id] = $value;

        return $this;
    }
}
