<?php

require_once __DIR__ . '/../MappIntelligenceExtendsTestCase.php';

/**
 * Class MappIntelligenceOrderTest
 */
class MappIntelligenceOrderTest extends MappIntelligenceExtendsTestCase
{
    public function testNewOrderWithoutValue()
    {
        $order = new MappIntelligenceOrder();

        $data = $order->getData();
        $this->assertEquals(0, $data['value']);
    }

    public function testNewOrderWithValue()
    {
        $order = new MappIntelligenceOrder(24.95);

        $data = $order->getData();
        $this->assertEquals(24.95, $data['value']);
    }

    public function testGetDefault()
    {
        $order = new MappIntelligenceOrder();

        $data = $order->getData();
        $this->assertEquals(0, $data['value']);
        $this->assertEquals('', $data['id']);
        $this->assertEquals('', $data['currency']);
        $this->assertEquals(0, $data['couponValue']);
        $this->assertEquals('', $data['paymentMethod']);
        $this->assertEquals('', $data['shippingService']);
        $this->assertEquals('', $data['shippingSpeed']);
        $this->assertEquals(0, $data['shippingCosts']);
        $this->assertEquals(0, $data['grossMargin']);
        $this->assertEquals('', $data['orderStatus']);
        $this->assertEquals(0, count($data['parameter']));
    }

    public function testSetParameter()
    {
        $order = new MappIntelligenceOrder();
        $order->setParameter(2, 'foo');
        $order->setParameter(15, 'bar');

        $data = $order->getData();
        $this->assertEquals('foo', $data['parameter'][2]);
        $this->assertEquals('bar', $data['parameter'][15]);
    }

    public function testSetValue()
    {
        $order = new MappIntelligenceOrder();
        $order->setValue(24.95);

        $data = $order->getData();
        $this->assertEquals(24.95, $data['value']);
    }

    public function testSetCouponValue()
    {
        $order = new MappIntelligenceOrder();
        $order->setCouponValue(10.99);

        $data = $order->getData();
        $this->assertEquals(10.99, $data['couponValue']);
    }

    public function testSetPaymentMethod()
    {
        $order = new MappIntelligenceOrder();
        $order->setPaymentMethod('paypal');

        $data = $order->getData();
        $this->assertEquals('paypal', $data['paymentMethod']);
    }

    public function testSetOrderStatus()
    {
        $order = new MappIntelligenceOrder();
        $order->setOrderStatus('payed');

        $data = $order->getData();
        $this->assertEquals('payed', $data['orderStatus']);
    }

    public function testSetId()
    {
        $order = new MappIntelligenceOrder();
        $order->setId('ABC123');

        $data = $order->getData();
        $this->assertEquals('ABC123', $data['id']);
    }

    public function testSetGrossMargin()
    {
        $order = new MappIntelligenceOrder();
        $order->setGrossMargin(6.95);

        $data = $order->getData();
        $this->assertEquals(6.95, $data['grossMargin']);
    }

    public function testSetShippingService()
    {
        $order = new MappIntelligenceOrder();
        $order->setShippingService('dhl');

        $data = $order->getData();
        $this->assertEquals('dhl', $data['shippingService']);
    }

    public function testSetShippingSpeed()
    {
        $order = new MappIntelligenceOrder();
        $order->setShippingSpeed('2d');

        $data = $order->getData();
        $this->assertEquals('2d', $data['shippingSpeed']);
    }

    public function testSetShippingCosts()
    {
        $order = new MappIntelligenceOrder();
        $order->setShippingCosts(3.95);

        $data = $order->getData();
        $this->assertEquals(3.95, $data['shippingCosts']);
    }

    public function testSetCurrency()
    {
        $order = new MappIntelligenceOrder();
        $order->setCurrency('EUR');

        $data = $order->getData();
        $this->assertEquals('EUR', $data['currency']);
    }

    public function testGetQueryParameter()
    {
        $order = new MappIntelligenceOrder();
        $order
            ->setValue(24.95)
            ->setCouponValue(10.99)
            ->setPaymentMethod('paypal')
            ->setOrderStatus('payed')
            ->setId('ABC123')
            ->setGrossMargin(6.95)
            ->setShippingService('dhl')
            ->setShippingSpeed('2d')
            ->setShippingCosts(3.95)
            ->setCurrency('EUR')
            ->setParameter(2, 'param2')
            ->setParameter(15, 'param15');

        $data = $order->getQueryParameter();
        $this->assertEquals(24.95, $data['ov']);
        $this->assertEquals('ABC123', $data['oi']);
        $this->assertEquals('EUR', $data['cr']);
        $this->assertEquals(10.99, $data['cb563']);
        $this->assertEquals('paypal', $data['cb761']);
        $this->assertEquals('dhl', $data['cb762']);
        $this->assertEquals('2d', $data['cb763']);
        $this->assertEquals(3.95, $data['cb764']);
        $this->assertEquals(6.95, $data['cb765']);
        $this->assertEquals('payed', $data['cb766']);
        $this->assertEquals('param2', $data['cb2']);
        $this->assertEquals('param15', $data['cb15']);
    }

    public function testGetDefaultQueryParameter()
    {
        $order = new MappIntelligenceOrder();

        $data = $order->getQueryParameter();
        $this->assertEquals(0, count($data));
    }
}
