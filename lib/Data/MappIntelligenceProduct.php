<?php

require_once __DIR__ . '/MappIntelligenceBasic.php';
require_once __DIR__ . '/../MappIntelligenceParameter.php';

/**
 * Class MappIntelligenceProduct
 */
class MappIntelligenceProduct extends MappIntelligenceBasic
{
    const VIEW = 'view';
    const BASKET = 'add';
    const ADD_TO_CART = 'add';
    const DELETE_FROM_CART = 'del';
    const CHECKOUT = 'checkout';
    const CONFIRMATION = 'conf';
    const ADD_TO_WISHLIST = 'add-wl';
    const DELETE_FROM_WISHLIST = 'del-wl';

    protected $id = '';
    protected $cost = 0;
    protected $quantity = 0;
    protected $status = self::VIEW;
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
            'id' => MappIntelligenceParameter::$PRODUCT_ID,
            'cost' => MappIntelligenceParameter::$PRODUCT_COST,
            'quantity' => MappIntelligenceParameter::$PRODUCT_QUANTITY,
            'status' => MappIntelligenceParameter::$PRODUCT_STATUS,
            'variant' => MappIntelligenceParameter::$PRODUCT_VARIANT,
            'soldOut' => MappIntelligenceParameter::$PRODUCT_SOLD_OUT,
            'parameter' => MappIntelligenceParameter::$CUSTOM_PRODUCT_PARAMETER,
            'category' => MappIntelligenceParameter::$CUSTOM_PRODUCT_CATEGORY
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
        if ($status === self::VIEW
            || $status === self::BASKET
            || $status === self::DELETE_FROM_CART
            || $status === self::CHECKOUT
            || $status === self::CONFIRMATION
            || $status === self::ADD_TO_WISHLIST
            || $status === self::DELETE_FROM_WISHLIST
        ) {
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
