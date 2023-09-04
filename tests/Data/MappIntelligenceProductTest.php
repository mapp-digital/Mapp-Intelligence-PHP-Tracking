<?php

require_once __DIR__ . '/../MappIntelligenceExtendsTestCase.php';

/**
 * Class MappIntelligenceProductTest
 */
class MappIntelligenceProductTest extends MappIntelligenceExtendsTestCase
{
    public function testNewProductWithoutId()
    {
        $product = new MappIntelligenceProduct();

        $data = $product->getData();
        $this->assertEmpty($data['id']);
    }

    public function testNewProductWithId()
    {
        $product = new MappIntelligenceProduct('foo.bar');

        $data = $product->getData();
        $this->assertEquals('foo.bar', $data['id']);
    }

    public function testGetDefault()
    {
        $product = new MappIntelligenceProduct();

        $data = $product->getData();
        $this->assertEquals('', $data['id']);
        $this->assertEquals(0, $data['cost']);
        $this->assertEquals(0, $data['quantity']);
        $this->assertEquals('view', $data['status']);
        $this->assertEquals('', $data['variant']);
        $this->assertEquals(false, $data['soldOut']);
        $this->assertEquals(0, count($data['parameter']));
        $this->assertEquals(0, count($data['category']));
    }

    public function testSetStatusDefault()
    {
        $product = new MappIntelligenceProduct();
        $product->setStatus('wishlist');

        $data = $product->getData();
        $this->assertEquals('view', $data['status']);
    }

    public function testSetStatusView()
    {
        $product = new MappIntelligenceProduct();
        $product->setStatus($product::VIEW);

        $data = $product->getData();
        $this->assertEquals('view', $data['status']);
    }

    public function testSetStatusBasket()
    {
        $product = new MappIntelligenceProduct();
        $product->setStatus($product::BASKET);

        $data = $product->getData();
        $this->assertEquals('add', $data['status']);
    }

    public function testSetStatusAddToCart()
    {
        $product = new MappIntelligenceProduct();
        $product->setStatus($product::ADD_TO_CART);

        $data = $product->getData();
        $this->assertEquals('add', $data['status']);
    }

    public function testSetStatusDeleteFromCart()
    {
        $product = new MappIntelligenceProduct();
        $product->setStatus($product::DELETE_FROM_CART);

        $data = $product->getData();
        $this->assertEquals('del', $data['status']);
    }

    public function testSetStatusCheckout()
    {
        $product = new MappIntelligenceProduct();
        $product->setStatus($product::CHECKOUT);

        $data = $product->getData();
        $this->assertEquals('checkout', $data['status']);
    }

    public function testSetStatusConfirmation()
    {
        $product = new MappIntelligenceProduct();
        $product->setStatus($product::CONFIRMATION);

        $data = $product->getData();
        $this->assertEquals('conf', $data['status']);
    }

    public function testSetStatusAddToWishlist()
    {
        $product = new MappIntelligenceProduct();
        $product->setStatus($product::ADD_TO_WISHLIST);

        $data = $product->getData();
        $this->assertEquals('add-wl', $data['status']);
    }

    public function testSetStatusDeleteFromWishlist()
    {
        $product = new MappIntelligenceProduct();
        $product->setStatus($product::DELETE_FROM_WISHLIST);

        $data = $product->getData();
        $this->assertEquals('del-wl', $data['status']);
    }

    public function testSetParameter()
    {
        $product = new MappIntelligenceProduct();
        $product->setParameter(2, 'foo');
        $product->setParameter(15, 'bar');

        $data = $product->getData();
        $this->assertEquals('foo', $data['parameter'][2]);
        $this->assertEquals('bar', $data['parameter'][15]);
    }

    public function testSetVariant()
    {
        $product = new MappIntelligenceProduct();
        $product->setVariant('red');

        $data = $product->getData();
        $this->assertEquals('red', $data['variant']);
    }

    public function testSetQuantity()
    {
        $product = new MappIntelligenceProduct();
        $product->setQuantity(5);

        $data = $product->getData();
        $this->assertEquals(5, $data['quantity']);
    }

    public function testSetId()
    {
        $product = new MappIntelligenceProduct();
        $product->setId('id of a product');

        $data = $product->getData();
        $this->assertEquals('id of a product', $data['id']);
    }

    public function testSetCategory()
    {
        $product = new MappIntelligenceProduct();
        $product->setCategory(2, 'foo');
        $product->setCategory(15, 'bar');

        $data = $product->getData();
        $this->assertEquals('foo', $data['category'][2]);
        $this->assertEquals('bar', $data['category'][15]);
    }

    public function testSetSoldOut()
    {
        $product = new MappIntelligenceProduct();
        $product->setSoldOut(true);

        $data = $product->getData();
        $this->assertEquals(true, $data['soldOut']);
    }

    public function testSetCost()
    {
        $product = new MappIntelligenceProduct();
        $product->setCost(19.95);

        $data = $product->getData();
        $this->assertEquals(19.95, $data['cost']);
    }

    public function testGetQueryParameter()
    {
        $product = new MappIntelligenceProduct();
        $product
            ->setStatus('add')
            ->setVariant('red')
            ->setQuantity(5)
            ->setId('id of a product')
            ->setSoldOut(true)
            ->setCost(19.95)
            ->setParameter(2, 'parameter 2')
            ->setParameter(15, 'parameter 15')
            ->setCategory(2, 'category 2')
            ->setCategory(15, 'category 15');

        $data = $product->getQueryParameter();
        $this->assertEquals('add', $data['st']);
        $this->assertEquals('red', $data['cb767']);
        $this->assertEquals(5, $data['qn']);
        $this->assertEquals('id of a product', $data['ba']);
        $this->assertEquals(true, $data['cb760']);
        $this->assertEquals(19.95, $data['co']);
        $this->assertEquals('parameter 2', $data['cb2']);
        $this->assertEquals('parameter 15', $data['cb15']);
        $this->assertEquals('category 2', $data['ca2']);
        $this->assertEquals('category 15', $data['ca15']);
    }

    public function testGetDefaultQueryParameter()
    {
        $product = new MappIntelligenceProduct();

        $data = $product->getQueryParameter();
        $this->assertEquals('', $data['ba']);
        $this->assertEquals(0, $data['co']);
        $this->assertEquals(0, $data['qn']);
        $this->assertEquals('view', $data['st']);
        $this->assertEquals('', $data['cb767']);
        $this->assertEquals(false, $data['cb760']);
    }
}
