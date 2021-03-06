<?php

require_once __DIR__ . '/MappIntelligenceBasic.php';
require_once __DIR__ . '/../MappIntelligenceParameter.php';

/**
 * Class MappIntelligenceOrder
 */
class MappIntelligenceOrder extends MappIntelligenceBasic
{
    protected $value = 0;
    protected $id = '';
    protected $currency = '';
    protected $couponValue = 0;
    protected $paymentMethod = '';
    protected $shippingService = '';
    protected $shippingSpeed = '';
    protected $shippingCosts = 0;
    protected $grossMargin = 0;
    protected $orderStatus = '';
    protected $parameter = array();

    /**
     * MappIntelligenceOrder constructor.
     * @param float $value
     */
    public function __construct($value = 0.0)
    {
        $this->value = $value;
    }

    /**
     * @return array
     */
    protected function getQueryList()
    {
        return array(
            'value' => MappIntelligenceParameter::$ORDER_VALUE,
            'id' => MappIntelligenceParameter::$ORDER_ID,
            'currency' => MappIntelligenceParameter::$CURRENCY,
            'couponValue' => MappIntelligenceParameter::$COUPON_VALUE,
            'paymentMethod' => MappIntelligenceParameter::$PAYMENT_METHOD,
            'shippingService' => MappIntelligenceParameter::$SHIPPING_SERVICE,
            'shippingSpeed' => MappIntelligenceParameter::$SHIPPING_SPEED,
            'shippingCosts' => MappIntelligenceParameter::$SHIPPING_COSTS,
            'grossMargin' => MappIntelligenceParameter::$GROSS_MARGIN,
            'orderStatus' => MappIntelligenceParameter::$ORDER_STATUS,
            'parameter' => MappIntelligenceParameter::$CUSTOM_PRODUCT_PARAMETER
        );
    }

    /**
     * @param float $value
     *
     * @return $this
     */
    public function setValue($value)
    {
        $this->value = $value;

        return $this;
    }

    /**
     * @param string $id
     *
     * @return $this
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @param string $currency
     *
     * @return $this
     */
    public function setCurrency($currency)
    {
        $this->currency = $currency;

        return $this;
    }

    /**
     * @param float $couponValue
     *
     * @return $this
     */
    public function setCouponValue($couponValue)
    {
        $this->couponValue = $couponValue;

        return $this;
    }

    /**
     * @param string $paymentMethod
     *
     * @return $this
     */
    public function setPaymentMethod($paymentMethod)
    {
        $this->paymentMethod = $paymentMethod;

        return $this;
    }

    /**
     * @param string $shippingService
     *
     * @return $this
     */
    public function setShippingService($shippingService)
    {
        $this->shippingService = $shippingService;

        return $this;
    }

    /**
     * @param string $shippingSpeed
     *
     * @return $this
     */
    public function setShippingSpeed($shippingSpeed)
    {
        $this->shippingSpeed = $shippingSpeed;

        return $this;
    }

    /**
     * @param float $shippingCosts
     *
     * @return $this
     */
    public function setShippingCosts($shippingCosts)
    {
        $this->shippingCosts = $shippingCosts;

        return $this;
    }

    /**
     * @param float $grossMargin
     *
     * @return $this
     */
    public function setGrossMargin($grossMargin)
    {
        $this->grossMargin = $grossMargin;

        return $this;
    }

    /**
     * @param string $orderStatus
     *
     * @return $this
     */
    public function setOrderStatus($orderStatus)
    {
        $this->orderStatus = $orderStatus;

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
}
