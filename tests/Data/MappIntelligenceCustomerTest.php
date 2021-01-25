<?php

require_once __DIR__ . '/../MappIntelligenceExtendsTestCase.php';

/**
 * Class MappIntelligenceCustomerTest
 */
class MappIntelligenceCustomerTest extends MappIntelligenceExtendsTestCase
{
    public function testNewCustomerWithoutId()
    {
        $customer = new MappIntelligenceCustomer();

        $data = $customer->getData();
        $this->assertEmpty($data['id']);
    }

    public function testNewCustomerWithId()
    {
        $customer = new MappIntelligenceCustomer('24');

        $data = $customer->getData();
        $this->assertEquals('24', $data['id']);
    }

    public function testGetDefault()
    {
        $customer = new MappIntelligenceCustomer();

        $data = $customer->getData();
        $this->assertEquals('', $data['id']);
        $this->assertEquals('', $data['email']);
        $this->assertEquals('', $data['emailRID']);
        $this->assertEquals(false, $data['emailOptin']);
        $this->assertEquals('', $data['firstName']);
        $this->assertEquals('', $data['lastName']);
        $this->assertEquals('', $data['telephone']);
        $this->assertEquals(0, $data['gender']);
        $this->assertEquals('', $data['birthday']);
        $this->assertEquals('', $data['country']);
        $this->assertEquals('', $data['city']);
        $this->assertEquals('', $data['postalCode']);
        $this->assertEquals('', $data['street']);
        $this->assertEquals('', $data['streetNumber']);
        $this->assertEquals(false, $data['validation']);
        $this->assertEquals(0, count($data['category']));
    }

    public function testSetFirstName()
    {
        $customer = new MappIntelligenceCustomer();
        $customer->setFirstName('John');

        $data = $customer->getData();
        $this->assertEquals('John', $data['firstName']);
    }

    public function testSetLastName()
    {
        $customer = new MappIntelligenceCustomer();
        $customer->setLastName('Doe');

        $data = $customer->getData();
        $this->assertEquals('Doe', $data['lastName']);
    }

    public function testSetStreetNumber()
    {
        $customer = new MappIntelligenceCustomer();
        $customer->setStreetNumber('4A');

        $data = $customer->getData();
        $this->assertEquals('4A', $data['streetNumber']);
    }

    public function testSetCountry()
    {
        $customer = new MappIntelligenceCustomer();
        $customer->setCountry('Germany');

        $data = $customer->getData();
        $this->assertEquals('Germany', $data['country']);
    }

    public function testSetPostalCode()
    {
        $customer = new MappIntelligenceCustomer();
        $customer->setPostalCode('12345');

        $data = $customer->getData();
        $this->assertEquals('12345', $data['postalCode']);
    }

    public function testSetEmailOptin()
    {
        $customer = new MappIntelligenceCustomer();
        $customer->setEmailOptin(true);

        $data = $customer->getData();
        $this->assertEquals(true, $data['emailOptin']);
    }

    public function testSetId()
    {
        $customer = new MappIntelligenceCustomer();
        $customer->setId('24');

        $data = $customer->getData();
        $this->assertEquals('24', $data['id']);
    }

    public function testSetTelephone()
    {
        $customer = new MappIntelligenceCustomer();
        $customer->setTelephone('+491234567890');

        $data = $customer->getData();
        $this->assertEquals('+491234567890', $data['telephone']);
    }

    public function testSetStreet()
    {
        $customer = new MappIntelligenceCustomer();
        $customer->setStreet('Robert-Koch-Platz');

        $data = $customer->getData();
        $this->assertEquals('Robert-Koch-Platz', $data['street']);
    }

    public function testSetGender()
    {
        $customer = new MappIntelligenceCustomer();
        $customer->setGender(1);

        $data = $customer->getData();
        $this->assertEquals(1, $data['gender']);
    }

    public function testSetEmailRID()
    {
        $customer = new MappIntelligenceCustomer();
        $customer->setEmailRID('ABC123-xyz789');

        $data = $customer->getData();
        $this->assertEquals('ABC123-xyz789', $data['emailRID']);
    }

    public function testSetEmail()
    {
        $customer = new MappIntelligenceCustomer();
        $customer->setEmail('foo@bar.com');

        $data = $customer->getData();
        $this->assertEquals('foo@bar.com', $data['email']);
    }

    public function testSetBirthday()
    {
        $customer = new MappIntelligenceCustomer();
        $customer->setBirthday('19900101');

        $data = $customer->getData();
        $this->assertEquals('19900101', $data['birthday']);
    }

    public function testSetCity()
    {
        $customer = new MappIntelligenceCustomer();
        $customer->setCity('Berlin');

        $data = $customer->getData();
        $this->assertEquals('Berlin', $data['city']);
    }

    public function testSetValidation()
    {
        $customer = new MappIntelligenceCustomer();
        $customer->setValidation(true);

        $data = $customer->getData();
        $this->assertEquals(true, $data['validation']);
    }

    public function testSetCategory()
    {
        $customer = new MappIntelligenceCustomer();
        $customer->setCategory(2, 'foo');
        $customer->setCategory(15, 'bar');

        $data = $customer->getData();
        $this->assertEquals('foo', $data['category'][2]);
        $this->assertEquals('bar', $data['category'][15]);
    }

    public function testGetQueryParameter()
    {
        $customer = new MappIntelligenceCustomer();
        $customer
            ->setFirstName('John')
            ->setLastName('Doe')
            ->setStreetNumber('4A')
            ->setCountry('Germany')
            ->setPostalCode('12345')
            ->setEmailOptin(true)
            ->setId('24')
            ->setTelephone('+491234567890')
            ->setStreet('Robert-Koch-Platz')
            ->setGender(1)
            ->setEmailRID('ABC123-xyz789')
            ->setEmail('foo@bar.com')
            ->setBirthday('19900101')
            ->setCity('Berlin')
            ->setValidation(true)
            ->setCategory(2, 'category2')
            ->setCategory(15, 'category15');

        $data = $customer->getQueryParameter();
        $this->assertEquals('24', $data['cd']);
        $this->assertEquals('foo@bar.com', $data['uc700']);
        $this->assertEquals('ABC123-xyz789', $data['uc701']);
        $this->assertEquals(true, $data['uc702']);
        $this->assertEquals('John', $data['uc703']);
        $this->assertEquals('Doe', $data['uc704']);
        $this->assertEquals('+491234567890', $data['uc705']);
        $this->assertEquals(1, $data['uc706']);
        $this->assertEquals('19900101', $data['uc707']);
        $this->assertEquals('Germany', $data['uc708']);
        $this->assertEquals('Berlin', $data['uc709']);
        $this->assertEquals('12345', $data['uc710']);
        $this->assertEquals('Robert-Koch-Platz', $data['uc711']);
        $this->assertEquals('4A', $data['uc712']);
        $this->assertEquals(true, $data['uc713']);
        $this->assertEquals('category2', $data['uc2']);
        $this->assertEquals('category15', $data['uc15']);
    }

    public function testGetDefaultQueryParameter()
    {
        $customer = new MappIntelligenceCustomer();

        $data = $customer->getQueryParameter();
        $this->assertEquals(0, count($data));
    }
}
