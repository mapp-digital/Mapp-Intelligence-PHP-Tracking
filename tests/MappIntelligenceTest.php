<?php

/**
 * Class MappIntelligenceTest
 */
class MappIntelligenceTest extends PHPUnit\Framework\TestCase
{
    public static $SET_COOKIE_ARGS = array();

    /**
     * before
     */
    public function setUp()
    {
        parent::setUp();

        runkit_function_rename('setcookie', 'origin_setcookie');
        runkit_function_add(
            'setcookie',
            '$name, $value = null, $expire = null, $path = null, $domain = null, $secure = null, $httponly = null',
            'MappIntelligenceTest::$SET_COOKIE_ARGS[] = func_get_args();'
        );
    }

    /**
     * after
     */
    public function tearDown()
    {
        parent::tearDown();

        self::$SET_COOKIE_ARGS = array();
        runkit_function_remove('setcookie');
        runkit_function_rename('origin_setcookie', 'setcookie');
    }

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
        $this->assertContains(
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
        $this->assertContains(
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
            'pn' => 'en.page.test'
        )));

        $requests = MappIntelligenceUnitUtil::getQueue(MappIntelligenceUnitUtil::getQueue($mappIntelligence));
        $this->assertEquals(1, count($requests));
        $this->assertRegExp('/^wt\?p=600,en\.page\.test,,,,,[0-9]{13},0,,\&$/', $requests[0]);
    }

    public function testSimpleData2()
    {
        $mappIntelligence = MappIntelligence::getInstance(array(
            'trackId' => '111111111111111',
            'trackDomain' => 'analytics01.wt-eu02.net'
        ));

        $this->assertEquals(true, $mappIntelligence->track(array(
            'pn' => 'en.page.test',
            'cg1' => 'page.test',
            'cg2' => 'en',
            'cg3' => 'page',
            'cg4' => 'test',
            'ov' => 360.93,
            'cd' => '24',
            'uc703' => 'John',
            'uc704' => 'Doe',
            'uc700' => 'john@doe.com',
            'cs1' => '1',
            'ba' => '065ee2b001;085eo2f009;995ee1k906',
            'co' => '59.99;49.99;15.99',
            'qn' => '1;5;1',
            'st' => 'conf'
        )));

        $requests = MappIntelligenceUnitUtil::getQueue(MappIntelligenceUnitUtil::getQueue($mappIntelligence));
        $this->assertEquals(1, count($requests));
        $this->assertRegExp('/^wt\?p=600,en\.page\.test,,,,,[0-9]{13},0,,\&/', $requests[0]);
        $this->assertRegExp('/\&cg1=page\.test/', $requests[0]);
        $this->assertRegExp('/\&cg2=en/', $requests[0]);
        $this->assertRegExp('/\&cg3=page/', $requests[0]);
        $this->assertRegExp('/\&cg4=test/', $requests[0]);
        $this->assertRegExp('/\&ov=360\.93/', $requests[0]);
        $this->assertRegExp('/\&cd=24/', $requests[0]);
        $this->assertRegExp('/\&uc703=John/', $requests[0]);
        $this->assertRegExp('/\&uc704=Doe/', $requests[0]);
        $this->assertRegExp('/\&uc700=john%40doe\.com/', $requests[0]);
        $this->assertRegExp('/\&cs1=1/', $requests[0]);
        $this->assertRegExp('/\&ba=065ee2b001%3B085eo2f009%3B995ee1k906/', $requests[0]);
        $this->assertRegExp('/\&co=59.99%3B49.99%3B15.99/', $requests[0]);
        $this->assertRegExp('/\&qn=1%3B5%3B1/', $requests[0]);
        $this->assertRegExp('/\&st=conf/', $requests[0]);
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
        $this->assertRegExp('/^wt\?p=600,en\.page\.test,,,,,[0-9]{13},0,,\&$/', $requests[0]);
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
        $this->assertRegExp('/^wt\?p=600,en\.page\.test,,,,,[0-9]{13},0,,\&/', $requests[0]);
        $this->assertRegExp('/\&cg1=page\.test/', $requests[0]);
        $this->assertRegExp('/\&cg2=en/', $requests[0]);
        $this->assertRegExp('/\&cg3=page/', $requests[0]);
        $this->assertRegExp('/\&cg4=test/', $requests[0]);
        $this->assertRegExp('/\&ov=360\.93/', $requests[0]);
        $this->assertRegExp('/\&cd=24/', $requests[0]);
        $this->assertRegExp('/\&uc703=John/', $requests[0]);
        $this->assertRegExp('/\&uc704=Doe/', $requests[0]);
        $this->assertRegExp('/\&uc700=john%40doe\.com/', $requests[0]);
        $this->assertRegExp('/\&cs1=1/', $requests[0]);
        $this->assertRegExp('/\&ba=065ee2b001%3B085eo2f009%3B995ee1k906%3Babc/', $requests[0]);
        $this->assertRegExp('/\&co=59.99%3B49.99%3B15.99%3B0/', $requests[0]);
        $this->assertRegExp('/\&qn=1%3B5%3B1%3B0/', $requests[0]);
        $this->assertRegExp('/\&cb760=0%3B0%3B0%3B1/', $requests[0]);
        $this->assertRegExp('/\&st=conf/', $requests[0]);
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
        $this->assertContains(
            'The Mapp Intelligence "trackDomain" and "trackId" are required to set user cookie',
            $fileContent[0]
        );
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

    public function testServerSideEverId()
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
        $this->assertRegExp('/^8[0-9]{18}$/', self::$SET_COOKIE_ARGS[0][1]);
        $this->assertEquals(true, is_numeric(self::$SET_COOKIE_ARGS[0][2]));
        $this->assertEquals('/', self::$SET_COOKIE_ARGS[0][3]);
        $this->assertEquals('domain.tld', self::$SET_COOKIE_ARGS[0][4]);
        $this->assertEquals(true, self::$SET_COOKIE_ARGS[0][5]);
        $this->assertEquals(true, self::$SET_COOKIE_ARGS[0][6]);
    }

    public function testNewV4ClientSideEverId()
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
        $this->assertRegExp('/^;111111111111111\|8[0-9]{18}$/', self::$SET_COOKIE_ARGS[0][1]);
        $this->assertEquals(true, is_numeric(self::$SET_COOKIE_ARGS[0][2]));
        $this->assertEquals('/', self::$SET_COOKIE_ARGS[0][3]);
        $this->assertEquals('', self::$SET_COOKIE_ARGS[0][4]);
        $this->assertEquals(true, self::$SET_COOKIE_ARGS[0][5]);
        $this->assertEquals(true, self::$SET_COOKIE_ARGS[0][6]);
    }

    public function testAppendV4ClientSideEverId()
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
        $this->assertRegExp(
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

    public function testNewV5ClientSideEverId()
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
        $this->assertRegExp('/^;111111111111111\|8[0-9]{18}$/', self::$SET_COOKIE_ARGS[0][1]);
        $this->assertEquals(true, is_numeric(self::$SET_COOKIE_ARGS[0][2]));
        $this->assertEquals('/', self::$SET_COOKIE_ARGS[0][3]);
        $this->assertEquals('', self::$SET_COOKIE_ARGS[0][4]);
        $this->assertEquals(true, self::$SET_COOKIE_ARGS[0][5]);
        $this->assertEquals(true, self::$SET_COOKIE_ARGS[0][6]);
    }

    public function testAppendV5ClientSideEverId()
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
        $this->assertRegExp(
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

    public function testNewSmartPixelClientSideEverId()
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
        $this->assertRegExp('/^8[0-9]{18}$/', self::$SET_COOKIE_ARGS[0][1]);
        $this->assertEquals(true, is_numeric(self::$SET_COOKIE_ARGS[0][2]));
        $this->assertEquals('/', self::$SET_COOKIE_ARGS[0][3]);
        $this->assertEquals('', self::$SET_COOKIE_ARGS[0][4]);
        $this->assertEquals(true, self::$SET_COOKIE_ARGS[0][5]);
        $this->assertEquals(true, self::$SET_COOKIE_ARGS[0][6]);
    }
}
