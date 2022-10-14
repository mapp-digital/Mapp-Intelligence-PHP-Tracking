<?php

require_once __DIR__ . '/MappIntelligenceExtendsTestCase.php';

class DataMappError
{
    /**
     * @throws Exception
     */
    public function build()
    {
        throw new Exception('test data map exception');
    }
}

/**
 * Class MappIntelligenceTestCase
 */
abstract class MappIntelligenceTestCase extends MappIntelligenceExtendsTestCase
{
    public static $SET_COOKIE_ARGS = array();

    /**
     * getInstance
     */
    public function testGetInstance()
    {
        $mappIntelligence = MappIntelligence::getInstance();
        $mappIntelligence2 = MappIntelligence::getInstance();
        $this->assertSame($mappIntelligence, $mappIntelligence2);
    }

    /**
     * track
     */
    public function testTrackIdIsRequired()
    {
        $mappIntelligence = MappIntelligence::getInstance(array(
            'trackDomain' => 'analytics01.wt-eu02.net',
            'debug' => true
        ));

        $this->assertEquals(false, $mappIntelligence->track());

        $fileContent = MappIntelligenceUnitUtil::getErrorLog();
        $this->assertContainsExtended(
            'The Mapp Intelligence "trackDomain" and "trackId" are required to track data',
            $fileContent[0]
        );
    }

    public function testTrackDomainIsRequired()
    {
        $mappIntelligence = MappIntelligence::getInstance(array(
            'trackId' => '111111111111111',
            'debug' => true
        ));

        $this->assertEquals(false, $mappIntelligence->track());

        $fileContent = MappIntelligenceUnitUtil::getErrorLog();
        $this->assertContainsExtended(
            'The Mapp Intelligence "trackDomain" and "trackId" are required to track data',
            $fileContent[0]
        );
    }

    public function testIncorrectDataType()
    {
        $mappIntelligence = MappIntelligence::getInstance(array(
            'trackId' => '111111111111111',
            'trackDomain' => 'analytics01.wt-eu02.net'
        ));

        $this->assertEquals(false, $mappIntelligence->track('foo.bar'));
    }

    public function testEmptyData()
    {
        $mappIntelligence = MappIntelligence::getInstance(array(
            'trackId' => '111111111111111',
            'trackDomain' => 'analytics01.wt-eu02.net'
        ));

        $this->assertEquals(false, $mappIntelligence->track());
    }

    public function testTrackingIsDeactivated()
    {
        $mappIntelligence = MappIntelligence::getInstance(array(
            'trackId' => '111111111111111',
            'trackDomain' => 'analytics01.wt-eu02.net',
            'deactivate' => true
        ));

        $mappIntelligence->flush();
        $this->assertEquals(false, $mappIntelligence->track(array(
            'pn' => 'en.page.test'
        )));

        $requests = MappIntelligenceUnitUtil::getQueue(MappIntelligenceUnitUtil::getQueue($mappIntelligence));
        $this->assertEquals(0, count($requests));
    }

    public function testSimpleData1()
    {
        $mappIntelligence = MappIntelligence::getInstance(array(
            'trackId' => '111111111111111',
            'trackDomain' => 'analytics01.wt-eu02.net'
        ));

        $this->assertEquals(true, $mappIntelligence->track(array(
            MappIntelligenceParameter::$PAGE_NAME => 'en.page.test'
        )));

        $requests = MappIntelligenceUnitUtil::getQueue(MappIntelligenceUnitUtil::getQueue($mappIntelligence));
        $this->assertEquals(1, count($requests));
        $this->assertRegExpExtended('/^wt\?p=600,en\.page\.test,,,,,[0-9]{13},0,,\&.+$/', $requests[0]);
        $this->assertTrue(MappIntelligenceUnitUtil::checkStatistics($requests[0], '14'));
    }

    public function testSimpleData2()
    {
        $mappIntelligence = MappIntelligence::getInstance(array(
            'trackId' => '111111111111111',
            'trackDomain' => 'analytics01.wt-eu02.net'
        ));

        $this->assertEquals(true, $mappIntelligence->track(array(
            MappIntelligenceParameter::$PAGE_NAME => 'en.page.test',
            MappIntelligenceCustomParameter::$CUSTOM_PAGE_CATEGORY->with(1) => 'page.test',
            MappIntelligenceCustomParameter::$CUSTOM_PAGE_CATEGORY->with(2) => 'en',
            (new MappIntelligenceCustomParameterMapping('cg'))->with(3) => 'page',
            MappIntelligenceCustomParameter::$CUSTOM_PAGE_CATEGORY->with(4) => 'test',
            MappIntelligenceParameter::$ORDER_VALUE => 360.93,
            MappIntelligenceParameter::$CUSTOMER_ID => '24',
            MappIntelligenceParameter::$FIRST_NAME => 'John',
            MappIntelligenceParameter::$LAST_NAME => 'Doe',
            MappIntelligenceParameter::$EMAIL => 'john@doe.com',
            MappIntelligenceCustomParameter::$CUSTOM_SESSION_PARAMETER->with(1) => '1',
            MappIntelligenceParameter::$PRODUCT_ID => '065ee2b001;085eo2f009;995ee1k906',
            MappIntelligenceParameter::$PRODUCT_COST => '59.99;49.99;15.99',
            MappIntelligenceParameter::$PRODUCT_QUANTITY => '1;5;1',
            MappIntelligenceParameter::$PRODUCT_STATUS => 'conf'
        )));

        $requests = MappIntelligenceUnitUtil::getQueue(MappIntelligenceUnitUtil::getQueue($mappIntelligence));
        $this->assertEquals(1, count($requests));
        $this->assertRegExpExtended('/^wt\?p=600,en\.page\.test,,,,,[0-9]{13},0,,\&/', $requests[0]);
        $this->assertRegExpExtended('/\&cg1=page\.test/', $requests[0]);
        $this->assertRegExpExtended('/\&cg2=en/', $requests[0]);
        $this->assertRegExpExtended('/\&cg3=page/', $requests[0]);
        $this->assertRegExpExtended('/\&cg4=test/', $requests[0]);
        $this->assertRegExpExtended('/\&ov=360\.93/', $requests[0]);
        $this->assertRegExpExtended('/\&cd=24/', $requests[0]);
        $this->assertRegExpExtended('/\&uc703=John/', $requests[0]);
        $this->assertRegExpExtended('/\&uc704=Doe/', $requests[0]);
        $this->assertRegExpExtended('/\&uc700=john%40doe\.com/', $requests[0]);
        $this->assertRegExpExtended('/\&cs1=1/', $requests[0]);
        $this->assertRegExpExtended('/\&ba=065ee2b001%3B085eo2f009%3B995ee1k906/', $requests[0]);
        $this->assertRegExpExtended('/\&co=59.99%3B49.99%3B15.99/', $requests[0]);
        $this->assertRegExpExtended('/\&qn=1%3B5%3B1/', $requests[0]);
        $this->assertRegExpExtended('/\&st=conf/', $requests[0]);
        $this->assertTrue(MappIntelligenceUnitUtil::checkStatistics($requests[0], '14'));
    }

    public function testParameterMap1()
    {
        $mappIntelligence = MappIntelligence::getInstance(array(
            'trackId' => '111111111111111',
            'trackDomain' => 'analytics01.wt-eu02.net'
        ));

        $parameter = new MappIntelligenceParameterMap();
        $parameter->add('pn', 'en.page.test');

        $this->assertEquals(true, $mappIntelligence->track($parameter));

        $requests = MappIntelligenceUnitUtil::getQueue(MappIntelligenceUnitUtil::getQueue($mappIntelligence));
        $this->assertEquals(1, count($requests));
        $this->assertRegExpExtended('/^wt\?p=600,en\.page\.test,,,,,[0-9]{13},0,,\&.+$/', $requests[0]);
        $this->assertTrue(MappIntelligenceUnitUtil::checkStatistics($requests[0], '14'));
    }

    public function testParameterMap2()
    {
        $mappIntelligence = MappIntelligence::getInstance(array(
            'trackId' => '111111111111111',
            'trackDomain' => 'analytics01.wt-eu02.net'
        ));

        $parameter = new MappIntelligenceParameterMap();
        $parameter->add('pn', 'en.page.test')
            ->add('cg1', 'page.test')
            ->add('cg2', 'en')
            ->add('cg3', 'page')
            ->add('cg4', 'test')
            ->add('ov', 360.93)
            ->add('cd', '24')
            ->add('uc703', 'John')
            ->add('uc704', 'Doe')
            ->add('uc700', 'john@doe.com')
            ->add('cs1', '1')
            ->add('ba', '065ee2b001;085eo2f009;995ee1k906')
            ->add('co', '59.99;49.99;15.99')
            ->add('qn', '1;5;1')
            ->add('st', 'conf');

        $this->assertEquals(true, $mappIntelligence->track($parameter));

        $requests = MappIntelligenceUnitUtil::getQueue(MappIntelligenceUnitUtil::getQueue($mappIntelligence));
        $this->assertEquals(1, count($requests));
        $this->assertRegExpExtended('/^wt\?p=600,en\.page\.test,,,,,[0-9]{13},0,,\&/', $requests[0]);
        $this->assertRegExpExtended('/\&cg1=page\.test/', $requests[0]);
        $this->assertRegExpExtended('/\&cg2=en/', $requests[0]);
        $this->assertRegExpExtended('/\&cg3=page/', $requests[0]);
        $this->assertRegExpExtended('/\&cg4=test/', $requests[0]);
        $this->assertRegExpExtended('/\&ov=360\.93/', $requests[0]);
        $this->assertRegExpExtended('/\&cd=24/', $requests[0]);
        $this->assertRegExpExtended('/\&uc703=John/', $requests[0]);
        $this->assertRegExpExtended('/\&uc704=Doe/', $requests[0]);
        $this->assertRegExpExtended('/\&uc700=john%40doe\.com/', $requests[0]);
        $this->assertRegExpExtended('/\&cs1=1/', $requests[0]);
        $this->assertRegExpExtended('/\&ba=065ee2b001%3B085eo2f009%3B995ee1k906/', $requests[0]);
        $this->assertRegExpExtended('/\&co=59.99%3B49.99%3B15.99/', $requests[0]);
        $this->assertRegExpExtended('/\&qn=1%3B5%3B1/', $requests[0]);
        $this->assertRegExpExtended('/\&st=conf/', $requests[0]);
        $this->assertTrue(MappIntelligenceUnitUtil::checkStatistics($requests[0], '14'));
    }

    public function testDataObject1()
    {
        $mappIntelligence = MappIntelligence::getInstance(array(
            'trackId' => '111111111111111',
            'trackDomain' => 'analytics01.wt-eu02.net'
        ));

        $this->assertEquals(true, $mappIntelligence->track(array(
            'page' => new MappIntelligencePage('en.page.test')
        )));

        $requests = MappIntelligenceUnitUtil::getQueue(MappIntelligenceUnitUtil::getQueue($mappIntelligence));
        $this->assertEquals(1, count($requests));
        $this->assertRegExpExtended('/^wt\?p=600,en\.page\.test,,,,,[0-9]{13},0,,\&.+$/', $requests[0]);
        $this->assertTrue(MappIntelligenceUnitUtil::checkStatistics($requests[0], '14'));
    }

    public function testDataObject2()
    {
        $mappIntelligence = MappIntelligence::getInstance(array(
            'trackId' => '111111111111111',
            'trackDomain' => 'analytics01.wt-eu02.net'
        ));

        $page = new MappIntelligencePage('en.page.test');
        $page->setCategory(1, 'page.test')
            ->setCategory(2, 'en')
            ->setCategory(3, 'page')
            ->setCategory(4, 'test');

        $session = new MappIntelligenceSession();
        $session->setParameter(1, '1');

        $customer = new MappIntelligenceCustomer('24');
        $customer->setFirstName('John')
            ->setLastName('Doe')
            ->setEmail('john@doe.com');

        $product1 = new MappIntelligenceProduct('065ee2b001');
        $product1->setCost(59.99)->setQuantity(1)->setStatus($product1::CONFIRMATION);

        $product2 = new MappIntelligenceProduct('085eo2f009');
        $product2->setCost(49.99)->setQuantity(5)->setStatus($product2::CONFIRMATION);

        $product3 = new MappIntelligenceProduct('995ee1k906');
        $product3->setCost(15.99)->setQuantity(1)->setStatus($product3::CONFIRMATION);

        $product4 = new MappIntelligenceProduct('abc');
        $product4->setCost(0)->setQuantity(0)->setSoldOut(true)->setStatus($product4::CONFIRMATION);


        $this->assertEquals(true, $mappIntelligence->track(array(
            'page' => $page,
            'order' => new MappIntelligenceOrder(360.93),
            'session' => $session,
            'customer' => $customer,
            'product' => array($product1, $product2, $product3, $product4)
        )));

        $requests = MappIntelligenceUnitUtil::getQueue(MappIntelligenceUnitUtil::getQueue($mappIntelligence));
        $this->assertEquals(1, count($requests));
        $this->assertRegExpExtended('/^wt\?p=600,en\.page\.test,,,,,[0-9]{13},0,,\&/', $requests[0]);
        $this->assertRegExpExtended('/\&cg1=page\.test/', $requests[0]);
        $this->assertRegExpExtended('/\&cg2=en/', $requests[0]);
        $this->assertRegExpExtended('/\&cg3=page/', $requests[0]);
        $this->assertRegExpExtended('/\&cg4=test/', $requests[0]);
        $this->assertRegExpExtended('/\&ov=360\.93/', $requests[0]);
        $this->assertRegExpExtended('/\&cd=24/', $requests[0]);
        $this->assertRegExpExtended('/\&uc703=John/', $requests[0]);
        $this->assertRegExpExtended('/\&uc704=Doe/', $requests[0]);
        $this->assertRegExpExtended('/\&uc700=john%40doe\.com/', $requests[0]);
        $this->assertRegExpExtended('/\&cs1=1/', $requests[0]);
        $this->assertRegExpExtended('/\&ba=065ee2b001%3B085eo2f009%3B995ee1k906%3Babc/', $requests[0]);
        $this->assertRegExpExtended('/\&co=59.99%3B49.99%3B15.99%3B0/', $requests[0]);
        $this->assertRegExpExtended('/\&qn=1%3B5%3B1%3B0/', $requests[0]);
        $this->assertRegExpExtended('/\&cb760=0%3B0%3B0%3B1/', $requests[0]);
        $this->assertRegExpExtended('/\&st=conf/', $requests[0]);
        $this->assertTrue(MappIntelligenceUnitUtil::checkStatistics($requests[0], '14'));
    }

    public function testDataMap1()
    {
        $mappIntelligence = MappIntelligence::getInstance(array(
            'trackId' => '111111111111111',
            'trackDomain' => 'analytics01.wt-eu02.net'
        ));

        $data = new MappIntelligenceDataMap();
        $data->page(new MappIntelligencePage('en.page.test'));
        $this->assertEquals(true, $mappIntelligence->track($data));

        $requests = MappIntelligenceUnitUtil::getQueue(MappIntelligenceUnitUtil::getQueue($mappIntelligence));
        $this->assertEquals(1, count($requests));
        $this->assertRegExpExtended('/^wt\?p=600,en\.page\.test,,,,,[0-9]{13},0,,\&.+$/', $requests[0]);
        $this->assertTrue(MappIntelligenceUnitUtil::checkStatistics($requests[0], '14'));
    }

    public function testDataMap2()
    {
        $mappIntelligence = MappIntelligence::getInstance(array(
            'trackId' => '111111111111111',
            'trackDomain' => 'analytics01.wt-eu02.net'
        ));

        $page = new MappIntelligencePage('en.page.test');
        $page->setCategory(1, 'page.test')
            ->setCategory(2, 'en')
            ->setCategory(3, 'page')
            ->setCategory(4, 'test');

        $session = new MappIntelligenceSession();
        $session->setParameter(1, '1');

        $customer = new MappIntelligenceCustomer('24');
        $customer->setFirstName('John')
            ->setLastName('Doe')
            ->setEmail('john@doe.com');

        $product1 = new MappIntelligenceProduct('065ee2b001');
        $product1->setCost(59.99)->setQuantity(1)->setStatus($product1::CONFIRMATION);

        $product2 = new MappIntelligenceProduct('085eo2f009');
        $product2->setCost(49.99)->setQuantity(5)->setStatus($product2::CONFIRMATION);

        $product3 = new MappIntelligenceProduct('995ee1k906');
        $product3->setCost(15.99)->setQuantity(1)->setStatus($product3::CONFIRMATION);

        $product4 = new MappIntelligenceProduct('abc');
        $product4->setCost(0)->setQuantity(0)->setSoldOut(true)->setStatus($product4::CONFIRMATION);

        $products = new MappIntelligenceProductCollection();
        $products->add($product1)
            ->add($product2)
            ->add($product3)
            ->add($product4);

        $data = new MappIntelligenceDataMap();
        $data->page($page)
            ->action(null)
            ->campaign(null)
            ->order(new MappIntelligenceOrder(360.93))
            ->session($session)
            ->customer($customer)
            ->product($products);

        $this->assertEquals(true, $mappIntelligence->track($data));

        $requests = MappIntelligenceUnitUtil::getQueue(MappIntelligenceUnitUtil::getQueue($mappIntelligence));
        $this->assertEquals(1, count($requests));
        $this->assertRegExpExtended('/^wt\?p=600,en\.page\.test,,,,,[0-9]{13},0,,\&/', $requests[0]);
        $this->assertRegExpExtended('/\&cg1=page\.test/', $requests[0]);
        $this->assertRegExpExtended('/\&cg2=en/', $requests[0]);
        $this->assertRegExpExtended('/\&cg3=page/', $requests[0]);
        $this->assertRegExpExtended('/\&cg4=test/', $requests[0]);
        $this->assertRegExpExtended('/\&ov=360\.93/', $requests[0]);
        $this->assertRegExpExtended('/\&cd=24/', $requests[0]);
        $this->assertRegExpExtended('/\&uc703=John/', $requests[0]);
        $this->assertRegExpExtended('/\&uc704=Doe/', $requests[0]);
        $this->assertRegExpExtended('/\&uc700=john%40doe\.com/', $requests[0]);
        $this->assertRegExpExtended('/\&cs1=1/', $requests[0]);
        $this->assertRegExpExtended('/\&ba=065ee2b001%3B085eo2f009%3B995ee1k906%3Babc/', $requests[0]);
        $this->assertRegExpExtended('/\&co=59.99%3B49.99%3B15.99%3B0/', $requests[0]);
        $this->assertRegExpExtended('/\&qn=1%3B5%3B1%3B0/', $requests[0]);
        $this->assertRegExpExtended('/\&cb760=0%3B0%3B0%3B1/', $requests[0]);
        $this->assertRegExpExtended('/\&st=conf/', $requests[0]);
        $this->assertTrue(MappIntelligenceUnitUtil::checkStatistics($requests[0], '14'));
    }

    public function testDataMap3()
    {
        $mappIntelligence = MappIntelligence::getInstance(array(
            'trackId' => '111111111111111',
            'trackDomain' => 'analytics01.wt-eu02.net'
        ));

        $this->assertEquals(true, $mappIntelligence->track(new DataMappError()));

        $requests = MappIntelligenceUnitUtil::getQueue(MappIntelligenceUnitUtil::getQueue($mappIntelligence));
        $this->assertEquals(1, count($requests));
        $this->assertRegExpExtended('/^wt\?p=600,0,,,,,[0-9]{13},0,,\&.+$/', $requests[0]);
        $this->assertTrue(MappIntelligenceUnitUtil::checkStatistics($requests[0], '14'));
    }

    /**
     * setUserId
     */
    public function testSetUserIdFailed()
    {
        $mappIntelligence = MappIntelligence::getInstance(array(
            'debug' => true
        ));

        $this->assertEquals(
            false,
            $mappIntelligence->setUserId($mappIntelligence::SMART, $mappIntelligence::CLIENT_SIDE_COOKIE)
        );

        $fileContent = MappIntelligenceUnitUtil::getErrorLog();
        $this->assertContainsExtended(
            'The Mapp Intelligence "trackDomain" and "trackId" are required for user cookie',
            $fileContent[0]
        );
    }

    public function testGetUserIdFailed()
    {
        $mappIntelligence = MappIntelligence::getInstance(array(
            'debug' => true
        ));

        $this->assertEquals(
            null,
            $mappIntelligence->getUserIdCookie($mappIntelligence::SMART, $mappIntelligence::CLIENT_SIDE_COOKIE)
        );

        $fileContent = MappIntelligenceUnitUtil::getErrorLog();
        $this->assertContainsExtended(
            'The Mapp Intelligence "trackDomain" and "trackId" are required for user cookie',
            $fileContent[0]
        );
    }

    public function testUndefinedPixelVersion()
    {
        $SET_COOKIE_ARGS = self::$SET_COOKIE_ARGS;

        $mappIntelligence = MappIntelligence::getInstance(array(
            'trackId' => '111111111111111',
            'trackDomain' => 'analytics01.wt-eu02.net'
        ));

        $this->assertEquals(
            true,
            $mappIntelligence->setUserId('v3', $mappIntelligence::CLIENT_SIDE_COOKIE)
        );
        $this->assertEquals(0, count($SET_COOKIE_ARGS));
    }

    public function testExistingEverId()
    {
        $SET_COOKIE_ARGS = self::$SET_COOKIE_ARGS;

        $mockSmartPixelEverId = '2157070685656224066';
        $cookie = array_key_exists('wtstp_eid', $_COOKIE) ? $_COOKIE['wtstp_eid'] : '';
        $_COOKIE['wtstp_eid'] = $mockSmartPixelEverId;

        $mappIntelligence = MappIntelligence::getInstance(array(
            'trackId' => '111111111111111',
            'trackDomain' => 'analytics01.wt-eu02.net'
        ));

        $this->assertEquals(
            true,
            $mappIntelligence->setUserId($mappIntelligence::SMART, $mappIntelligence::SERVER_SIDE_COOKIE)
        );
        $this->assertEquals(0, count($SET_COOKIE_ARGS));

        $_COOKIE['wtstp_eid'] = $cookie;
    }

    public function testIgnoreServerSideEverIdWithMappIntelligenceTrackDomain()
    {
        $mappIntelligence = MappIntelligence::getInstance(array(
            'trackId' => '111111111111111',
            'trackDomain' => 'analytics01.wt-eu02.net'
        ));

        $this->assertEquals(
            true,
            $mappIntelligence->setUserId($mappIntelligence::SMART, $mappIntelligence::SERVER_SIDE_COOKIE)
        );
        $this->assertEquals(0, count(self::$SET_COOKIE_ARGS));
    }

    public function testSetServerSideEverId()
    {
        $mappIntelligence = MappIntelligence::getInstance(array(
            'trackId' => '111111111111111',
            'trackDomain' => 'analytics.domain.tld'
        ));

        $this->assertEquals(
            true,
            $mappIntelligence->setUserId($mappIntelligence::SMART, $mappIntelligence::SERVER_SIDE_COOKIE)
        );
        $this->assertEquals('wteid_111111111111111', self::$SET_COOKIE_ARGS[0][0]);
        $this->assertRegExpExtended('/^8[0-9]{18}$/', self::$SET_COOKIE_ARGS[0][1]);
        $this->assertEquals(true, is_numeric(self::$SET_COOKIE_ARGS[0][2]));
        $this->assertEquals('/', self::$SET_COOKIE_ARGS[0][3]);
        $this->assertEquals('domain.tld', self::$SET_COOKIE_ARGS[0][4]);
        $this->assertEquals(true, self::$SET_COOKIE_ARGS[0][5]);
        $this->assertEquals(true, self::$SET_COOKIE_ARGS[0][6]);
    }

    public function testSetNewV4ClientSideEverId()
    {
        $mappIntelligence = MappIntelligence::getInstance(array(
            'trackId' => '111111111111111',
            'trackDomain' => 'analytics.domain.tld'
        ));

        $this->assertEquals(
            true,
            $mappIntelligence->setUserId($mappIntelligence::V4, $mappIntelligence::CLIENT_SIDE_COOKIE)
        );
        $this->assertEquals('wt3_eid', self::$SET_COOKIE_ARGS[0][0]);
        $this->assertRegExpExtended('/^;111111111111111\|8[0-9]{18}$/', self::$SET_COOKIE_ARGS[0][1]);
        $this->assertEquals(true, is_numeric(self::$SET_COOKIE_ARGS[0][2]));
        $this->assertEquals('/', self::$SET_COOKIE_ARGS[0][3]);
        $this->assertEquals('', self::$SET_COOKIE_ARGS[0][4]);
        $this->assertEquals(true, self::$SET_COOKIE_ARGS[0][5]);
        $this->assertEquals(true, self::$SET_COOKIE_ARGS[0][6]);
    }

    public function testSetAppendV4ClientSideEverId()
    {
        $mockOldPixelEverIdCookie = '';
        $mockOldPixelEverIdCookie .= ';385255285199574|2155991359000202180#2157830383874881775';
        $mockOldPixelEverIdCookie .= ';222222222222222|2155991359353080227#2157830383339928168';
        $mockOldPixelEverIdCookie .= ';100000020686800|2155991359000202180#2156076321300417449';
        $cookie = array_key_exists('wt3_eid', $_COOKIE) ? $_COOKIE['wt3_eid'] : '';
        $_COOKIE['wt3_eid'] = $mockOldPixelEverIdCookie;

        $mappIntelligence = MappIntelligence::getInstance(array(
            'trackId' => '111111111111111',
            'trackDomain' => 'analytics.domain.tld'
        ));

        $this->assertEquals(
            true,
            $mappIntelligence->setUserId($mappIntelligence::V4, $mappIntelligence::CLIENT_SIDE_COOKIE)
        );
        $this->assertEquals('wt3_eid', self::$SET_COOKIE_ARGS[0][0]);
        $this->assertRegExpExtended(
            '/^' . preg_quote($mockOldPixelEverIdCookie) . ';111111111111111\|8[0-9]{18}$/',
            self::$SET_COOKIE_ARGS[0][1]
        );
        $this->assertEquals(true, is_numeric(self::$SET_COOKIE_ARGS[0][2]));
        $this->assertEquals('/', self::$SET_COOKIE_ARGS[0][3]);
        $this->assertEquals('', self::$SET_COOKIE_ARGS[0][4]);
        $this->assertEquals(true, self::$SET_COOKIE_ARGS[0][5]);
        $this->assertEquals(true, self::$SET_COOKIE_ARGS[0][6]);

        $_COOKIE['wt3_eid'] = $cookie;
    }

    public function testSetNewV5ClientSideEverId()
    {
        $mappIntelligence = MappIntelligence::getInstance(array(
            'trackId' => '111111111111111',
            'trackDomain' => 'analytics.domain.tld'
        ));

        $this->assertEquals(
            true,
            $mappIntelligence->setUserId($mappIntelligence::V5, $mappIntelligence::CLIENT_SIDE_COOKIE)
        );
        $this->assertEquals('wt3_eid', self::$SET_COOKIE_ARGS[0][0]);
        $this->assertRegExpExtended('/^;111111111111111\|8[0-9]{18}$/', self::$SET_COOKIE_ARGS[0][1]);
        $this->assertEquals(true, is_numeric(self::$SET_COOKIE_ARGS[0][2]));
        $this->assertEquals('/', self::$SET_COOKIE_ARGS[0][3]);
        $this->assertEquals('', self::$SET_COOKIE_ARGS[0][4]);
        $this->assertEquals(true, self::$SET_COOKIE_ARGS[0][5]);
        $this->assertEquals(true, self::$SET_COOKIE_ARGS[0][6]);
    }

    public function testSetAppendV5ClientSideEverId()
    {
        $mockOldPixelEverIdCookie = '';
        $mockOldPixelEverIdCookie .= ';385255285199574|2155991359000202180#2157830383874881775';
        $mockOldPixelEverIdCookie .= ';222222222222222|2155991359353080227#2157830383339928168';
        $mockOldPixelEverIdCookie .= ';100000020686800|2155991359000202180#2156076321300417449';

        $cookie = array_key_exists('wt3_eid', $_COOKIE) ? $_COOKIE['wt3_eid'] : '';
        $_COOKIE['wt3_eid'] = $mockOldPixelEverIdCookie;

        $mappIntelligence = MappIntelligence::getInstance(array(
            'trackId' => '111111111111111',
            'trackDomain' => 'analytics.domain.tld'
        ));

        $this->assertEquals(
            true,
            $mappIntelligence->setUserId($mappIntelligence::V5, $mappIntelligence::CLIENT_SIDE_COOKIE)
        );
        $this->assertEquals('wt3_eid', self::$SET_COOKIE_ARGS[0][0]);
        $this->assertRegExpExtended(
            '/^' . preg_quote($mockOldPixelEverIdCookie) . ';111111111111111\|8[0-9]{18}$/',
            self::$SET_COOKIE_ARGS[0][1]
        );
        $this->assertEquals(true, is_numeric(self::$SET_COOKIE_ARGS[0][2]));
        $this->assertEquals('/', self::$SET_COOKIE_ARGS[0][3]);
        $this->assertEquals('', self::$SET_COOKIE_ARGS[0][4]);
        $this->assertEquals(true, self::$SET_COOKIE_ARGS[0][5]);
        $this->assertEquals(true, self::$SET_COOKIE_ARGS[0][6]);

        $_COOKIE['wt3_eid'] = $cookie;
    }

    public function testSetNewSmartPixelClientSideEverId()
    {
        $mappIntelligence = MappIntelligence::getInstance(array(
            'trackId' => '111111111111111',
            'trackDomain' => 'analytics.domain.tld'
        ));

        $this->assertEquals(
            true,
            $mappIntelligence->setUserId($mappIntelligence::SMART, $mappIntelligence::CLIENT_SIDE_COOKIE)
        );
        $this->assertEquals('wtstp_eid', self::$SET_COOKIE_ARGS[0][0]);
        $this->assertRegExpExtended('/^8[0-9]{18}$/', self::$SET_COOKIE_ARGS[0][1]);
        $this->assertEquals(true, is_numeric(self::$SET_COOKIE_ARGS[0][2]));
        $this->assertEquals('/', self::$SET_COOKIE_ARGS[0][3]);
        $this->assertEquals('', self::$SET_COOKIE_ARGS[0][4]);
        $this->assertEquals(true, self::$SET_COOKIE_ARGS[0][5]);
        $this->assertEquals(true, self::$SET_COOKIE_ARGS[0][6]);
    }

    public function testGetServerSideEverId()
    {
        $mappIntelligence = MappIntelligence::getInstance(array(
            'trackId' => '111111111111111',
            'trackDomain' => 'analytics.domain.tld'
        ));

        $c = $mappIntelligence->getUserIdCookie($mappIntelligence::SMART, $mappIntelligence::SERVER_SIDE_COOKIE);
        $this->assertEquals('wteid_111111111111111', $c->getName());
        $this->assertRegExpExtended('/^8[0-9]{18}$/', $c->getValue());
        $this->assertEquals(true, is_numeric($c->getMaxAge()));
        $this->assertEquals('/', $c->getPath());
        $this->assertEquals('domain.tld', $c->getDomain());
        $this->assertEquals(true, $c->isSecure());
        $this->assertEquals(true, $c->isHttpOnly());
    }

    public function testGetNewV4ClientSideEverId()
    {
        $mappIntelligence = MappIntelligence::getInstance(array(
            'trackId' => '111111111111111',
            'trackDomain' => 'analytics.domain.tld'
        ));

        $c = $mappIntelligence->getUserIdCookie($mappIntelligence::V4, $mappIntelligence::CLIENT_SIDE_COOKIE);
        $this->assertEquals('wt3_eid', $c->getName());
        $this->assertRegExpExtended('/^;111111111111111\|8[0-9]{18}$/', $c->getValue());
        $this->assertEquals(true, is_numeric($c->getMaxAge()));
        $this->assertEquals('/', $c->getPath());
        $this->assertEquals('', $c->getDomain());
        $this->assertEquals(true, $c->isSecure());
        $this->assertEquals(true, $c->isHttpOnly());
    }

    public function testGetAppendV4ClientSideEverId()
    {
        $mockOldPixelEverIdCookie = '';
        $mockOldPixelEverIdCookie .= ';385255285199574|2155991359000202180#2157830383874881775';
        $mockOldPixelEverIdCookie .= ';222222222222222|2155991359353080227#2157830383339928168';
        $mockOldPixelEverIdCookie .= ';100000020686800|2155991359000202180#2156076321300417449';
        $cookie = array_key_exists('wt3_eid', $_COOKIE) ? $_COOKIE['wt3_eid'] : '';
        $_COOKIE['wt3_eid'] = $mockOldPixelEverIdCookie;

        $mappIntelligence = MappIntelligence::getInstance(array(
            'trackId' => '111111111111111',
            'trackDomain' => 'analytics.domain.tld'
        ));

        $c = $mappIntelligence->getUserIdCookie($mappIntelligence::V4, $mappIntelligence::CLIENT_SIDE_COOKIE);
        $this->assertEquals('wt3_eid', $c->getName());
        $this->assertRegExpExtended(
            '/^' . preg_quote($mockOldPixelEverIdCookie) . ';111111111111111\|8[0-9]{18}$/',
            $c->getValue()
        );
        $this->assertEquals(true, is_numeric($c->getMaxAge()));
        $this->assertEquals('/', $c->getPath());
        $this->assertEquals('', $c->getDomain());
        $this->assertEquals(true, $c->isSecure());
        $this->assertEquals(true, $c->isHttpOnly());

        $_COOKIE['wt3_eid'] = $cookie;
    }

    public function testGetNewV5ClientSideEverId()
    {
        $mappIntelligence = MappIntelligence::getInstance(array(
            'trackId' => '111111111111111',
            'trackDomain' => 'analytics.domain.tld'
        ));

        $c = $mappIntelligence->getUserIdCookie($mappIntelligence::V5, $mappIntelligence::CLIENT_SIDE_COOKIE);
        $this->assertEquals('wt3_eid', $c->getName());
        $this->assertRegExpExtended('/^;111111111111111\|8[0-9]{18}$/', $c->getValue());
        $this->assertEquals(true, is_numeric($c->getMaxAge()));
        $this->assertEquals('/', $c->getPath());
        $this->assertEquals('', $c->getDomain());
        $this->assertEquals(true, $c->isSecure());
        $this->assertEquals(true, $c->isHttpOnly());
    }

    public function testGetAppendV5ClientSideEverId()
    {
        $mockOldPixelEverIdCookie = '';
        $mockOldPixelEverIdCookie .= ';385255285199574|2155991359000202180#2157830383874881775';
        $mockOldPixelEverIdCookie .= ';222222222222222|2155991359353080227#2157830383339928168';
        $mockOldPixelEverIdCookie .= ';100000020686800|2155991359000202180#2156076321300417449';

        $cookie = array_key_exists('wt3_eid', $_COOKIE) ? $_COOKIE['wt3_eid'] : '';
        $_COOKIE['wt3_eid'] = $mockOldPixelEverIdCookie;

        $mappIntelligence = MappIntelligence::getInstance(array(
            'trackId' => '111111111111111',
            'trackDomain' => 'analytics.domain.tld'
        ));

        $c = $mappIntelligence->getUserIdCookie($mappIntelligence::V5, $mappIntelligence::CLIENT_SIDE_COOKIE);
        $this->assertEquals('wt3_eid', $c->getName());
        $this->assertRegExpExtended(
            '/^' . preg_quote($mockOldPixelEverIdCookie) . ';111111111111111\|8[0-9]{18}$/',
            $c->getValue()
        );
        $this->assertEquals(true, is_numeric($c->getMaxAge()));
        $this->assertEquals('/', $c->getPath());
        $this->assertEquals('', $c->getDomain());
        $this->assertEquals(true, $c->isSecure());
        $this->assertEquals(true, $c->isHttpOnly());

        $_COOKIE['wt3_eid'] = $cookie;
    }

    public function testGetNewSmartPixelClientSideEverId()
    {
        $mappIntelligence = MappIntelligence::getInstance(array(
            'trackId' => '111111111111111',
            'trackDomain' => 'analytics.domain.tld'
        ));

        $c = $mappIntelligence->getUserIdCookie($mappIntelligence::SMART, $mappIntelligence::CLIENT_SIDE_COOKIE);
        $this->assertEquals('wtstp_eid', $c->getName());
        $this->assertRegExpExtended('/^8[0-9]{18}$/', $c->getValue());
        $this->assertEquals(true, is_numeric($c->getMaxAge()));
        $this->assertEquals('/', $c->getPath());
        $this->assertEquals('', $c->getDomain());
        $this->assertEquals(true, $c->isSecure());
        $this->assertEquals(true, $c->isHttpOnly());
    }
}
