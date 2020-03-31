<?php

/**
 * Class MappIntelligenceQueueTest
 */
class MappIntelligenceQueueTest extends PHPUnit\Framework\TestCase
{
    /**
     * after
     */
    public function tearDown()
    {
        parent::tearDown();

        $_COOKIE = array();
        unset($_SERVER['HTTP_USER_AGENT']);
        unset($_SERVER['REMOTE_ADDR']);
        unset($_SERVER['HTTP_HOST']);
        unset($_SERVER['HTTP_REFERER']);
        unset($_SERVER['REQUEST_URI']);
    }

    /**
     * add
     */
    public function testAddTrackingRequest()
    {
        $queue = new MappIntelligenceQueue(array(
            'trackId' => '111111111111111',
            'trackDomain' => 'analytics01.wt-eu02.net',
            'debug' => true
        ));

        $queue->add('wt?p=300,0');

        $requests = MappIntelligenceUnitUtil::getQueue($queue);
        $this->assertEquals(1, count($requests));
        $this->assertEquals('wt?p=300,0', $requests[0]);
    }

    public function testAddEmptyTrackingData()
    {
        $queue = new MappIntelligenceQueue(array(
            'trackId' => '111111111111111',
            'trackDomain' => 'analytics01.wt-eu02.net',
            'debug' => true
        ));

        $queue->add(array());

        $requests = MappIntelligenceUnitUtil::getQueue($queue);
        $this->assertEquals(1, count($requests));
        $this->assertRegExp('/^wt\?p=600,0,,,,,[0-9]{13},0,,\&$/', $requests[0]);
    }

    public function testMaxBatchSize()
    {
        $queue = new MappIntelligenceQueue(array(
            'trackId' => '111111111111111',
            'trackDomain' => 'analytics01.wt-eu02.net',
            'debug' => true,
            'maxBatchSize' => 10
        ));

        for ($i = 0; $i < 15; $i++) {
            $queue->add(array(
                'pn' => $i . ''
            ));
        }

        $fileContent = MappIntelligenceUnitUtil::getErrorLog();
        $this->assertContains('Sent batch requests, current queue size is 10 req.', $fileContent[10]);
        $this->assertContains('Sent batch requests, current queue size is 11 req.', $fileContent[17]);
        $this->assertContains('Sent batch requests, current queue size is 12 req.', $fileContent[24]);
        $this->assertContains('Sent batch requests, current queue size is 13 req.', $fileContent[31]);
        $this->assertContains('Sent batch requests, current queue size is 14 req.', $fileContent[38]);
        $this->assertContains('Sent batch requests, current queue size is 15 req.', $fileContent[45]);

        $requests = MappIntelligenceUnitUtil::getQueue($queue);
        $this->assertEquals(15, count($requests));
        for ($i = 0; $i < 15; $i++) {
            $this->assertRegExp('/^wt\?p=600,' . $i . ',,,,,[0-9]{13},0,,\&$/', $requests[$i]);
        }
    }

    public function testWithUserAgent()
    {
        $queue = new MappIntelligenceQueue(array(
            'trackId' => '111111111111111',
            'trackDomain' => 'analytics01.wt-eu02.net',
            'debug' => true
        ));

        $mockUserAgent = 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:71.0) Gecko/20100101 Firefox/71.0';
        $_SERVER['HTTP_USER_AGENT'] = $mockUserAgent;

        $queue->add(array());

        $requests = MappIntelligenceUnitUtil::getQueue($queue);
        $this->assertEquals(1, count($requests));
        $this->assertRegExp('/^wt\?p=600,0,,,,,[0-9]{13},0,,\&/', $requests[0]);
        $this->assertRegExp('/\&X\-WT\-UA=' . preg_quote(rawurlencode($mockUserAgent)) . '/', $requests[0]);
    }

    public function testWithUserAgent2()
    {
        $queue = new MappIntelligenceQueue(array(
            'trackId' => '111111111111111',
            'trackDomain' => 'analytics01.wt-eu02.net',
            'debug' => true
        ));

        $mockUserAgent = 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:71.0) Gecko/20100101 Firefox/71.0';
        $_SERVER['HTTP_USER_AGENT'] = $mockUserAgent;

        $queue->add('wt?p=300,0');

        $requests = MappIntelligenceUnitUtil::getQueue($queue);
        $this->assertEquals(1, count($requests));
        $this->assertRegExp('/^wt\?p=300,0\&/', $requests[0]);
        $this->assertRegExp('/\&X\-WT\-UA=' . preg_quote(rawurlencode($mockUserAgent)) . '/', $requests[0]);
    }

    public function testWithUserAgent3()
    {
        $queue = new MappIntelligenceQueue(array(
            'trackId' => '111111111111111',
            'trackDomain' => 'analytics01.wt-eu02.net',
            'debug' => true
        ));

        $mockUserAgent = 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:71.0) Gecko/20100101 Firefox/71.0';
        $_SERVER['HTTP_USER_AGENT'] = $mockUserAgent;

        $queue->add('wt?p=300,0&X-WT-UA=test');

        $requests = MappIntelligenceUnitUtil::getQueue($queue);
        $this->assertEquals(1, count($requests));
        $this->assertRegExp('/^wt\?p=300,0\&/', $requests[0]);
        $this->assertRegExp('/\&X\-WT\-UA=test/', $requests[0]);
    }

    public function testWithRemoteAddr()
    {
        $queue = new MappIntelligenceQueue(array(
            'trackId' => '111111111111111',
            'trackDomain' => 'analytics01.wt-eu02.net',
            'debug' => true
        ));

        $mockRemoteAddr = '127.0.0.1';
        $_SERVER['REMOTE_ADDR'] = $mockRemoteAddr;

        $queue->add(array());

        $requests = MappIntelligenceUnitUtil::getQueue($queue);
        $this->assertEquals(1, count($requests));
        $this->assertRegExp('/^wt\?p=600,0,,,,,[0-9]{13},0,,\&/', $requests[0]);
        $this->assertRegExp('/\&X\-WT\-IP=' . preg_quote(rawurlencode($mockRemoteAddr)) . '/', $requests[0]);
    }

    public function testWithRemoteAddr2()
    {
        $queue = new MappIntelligenceQueue(array(
            'trackId' => '111111111111111',
            'trackDomain' => 'analytics01.wt-eu02.net',
            'debug' => true
        ));

        $mockRemoteAddr = '127.0.0.1';
        $_SERVER['REMOTE_ADDR'] = $mockRemoteAddr;

        $queue->add('wt?p=300,0');

        $requests = MappIntelligenceUnitUtil::getQueue($queue);
        $this->assertEquals(1, count($requests));
        $this->assertRegExp('/^wt\?p=300,0\&/', $requests[0]);
        $this->assertRegExp('/\&X\-WT\-IP=' . preg_quote(rawurlencode($mockRemoteAddr)) . '/', $requests[0]);
    }

    public function testWithRemoteAddr3()
    {
        $queue = new MappIntelligenceQueue(array(
            'trackId' => '111111111111111',
            'trackDomain' => 'analytics01.wt-eu02.net',
            'debug' => true
        ));

        $mockRemoteAddr = '127.0.0.1';
        $_SERVER['REMOTE_ADDR'] = $mockRemoteAddr;

        $queue->add('wt?p=300,0&X-WT-IP=127.0.0.20');

        $requests = MappIntelligenceUnitUtil::getQueue($queue);
        $this->assertEquals(1, count($requests));
        $this->assertRegExp('/^wt\?p=300,0\&/', $requests[0]);
        $this->assertRegExp('/\&X\-WT\-IP=127\.0\.0\.20/', $requests[0]);
    }

    public function testWithRequestURI()
    {
        $queue = new MappIntelligenceQueue(array(
            'trackId' => '111111111111111',
            'trackDomain' => 'analytics01.wt-eu02.net',
            'debug' => true
        ));

        $mockHTTPHost = 'sub.domain.tld';
        $mockRequestURI = '/path/to/page.html';

        $_SERVER['HTTP_HOST'] = $mockHTTPHost;
        $_SERVER['REQUEST_URI'] = $mockRequestURI;

        $queue->add(array());

        $requests = MappIntelligenceUnitUtil::getQueue($queue);
        $this->assertEquals(1, count($requests));
        $this->assertRegExp(
            '/^wt\?p=600,' . preg_quote(rawurlencode($mockHTTPHost . $mockRequestURI)) . ',,,,,[0-9]{13},0,,\&/',
            $requests[0]
        );
        $this->assertRegExp(
            '/\&pu=' . preg_quote(rawurlencode('https://' . $mockHTTPHost . $mockRequestURI)) . '/',
            $requests[0]
        );
    }

    public function testWithSmartPixelEverId()
    {
        $mockSmartPixelEverId = '2157070685656224066';
        $cookie = array_key_exists('wtstp_eid', $_COOKIE) ? $_COOKIE['wtstp_eid'] : '';
        $_COOKIE['wtstp_eid'] = $mockSmartPixelEverId;

        $queue = new MappIntelligenceQueue(array(
            'trackId' => '111111111111111',
            'trackDomain' => 'analytics01.wt-eu02.net',
            'debug' => true
        ));

        $queue->add(array());

        $requests = MappIntelligenceUnitUtil::getQueue($queue);
        $this->assertEquals(1, count($requests));
        $this->assertRegExp('/^wt\?p=600,0,,,,,[0-9]{13},0,,\&/', $requests[0]);
        $this->assertRegExp('/\&eid=' . $mockSmartPixelEverId . '/', $requests[0]);

        $_COOKIE['wtstp_eid'] = $cookie;
    }

    public function testWithTrackServerEverId()
    {
        $mockTrackServerEverId = '6157070685656224066';
        $cookie = array_key_exists('wteid_111111111111111', $_COOKIE) ? $_COOKIE['wteid_111111111111111'] : '';
        $_COOKIE['wteid_111111111111111'] = $mockTrackServerEverId;

        $queue = new MappIntelligenceQueue(array(
            'trackId' => '111111111111111',
            'trackDomain' => 'analytics01.wt-eu02.net',
            'debug' => true
        ));

        $queue->add(array());

        $requests = MappIntelligenceUnitUtil::getQueue($queue);
        $this->assertEquals(1, count($requests));
        $this->assertRegExp('/^wt\?p=600,0,,,,,[0-9]{13},0,,\&/', $requests[0]);
        $this->assertRegExp('/\&eid=' . $mockTrackServerEverId . '/', $requests[0]);

        $_COOKIE['wteid_111111111111111'] = $cookie;
    }

    public function testWithoutOldPixelEverId()
    {
        $mockOldPixelEverIdCookie = ';385255285199574|2155991359000202180#2157830383874881775';
        $mockOldPixelEverIdCookie .= ';222222222222222|2155991359353080227#2157830383339928168';
        $mockOldPixelEverIdCookie .= ';100000020686800|2155991359000202180#2156076321300417449';
        $cookie = array_key_exists('wt3_eid', $_COOKIE) ? $_COOKIE['wt3_eid'] : '';
        $_COOKIE['wt3_eid'] = $mockOldPixelEverIdCookie;

        $queue = new MappIntelligenceQueue(array(
            'trackId' => '111111111111111',
            'trackDomain' => 'analytics01.wt-eu02.net',
            'debug' => true
        ));

        $queue->add(array());

        $requests = MappIntelligenceUnitUtil::getQueue($queue);
        $this->assertEquals(1, count($requests));
        $this->assertRegExp('/^wt\?p=600,0,,,,,[0-9]{13},0,,\&$/', $requests[0]);

        $_COOKIE['wt3_eid'] = $cookie;
    }

    public function testWithOldPixelEverId()
    {
        $mockOldPixelEverIdCookie = ';385255285199574|2155991359000202180#2157830383874881775';
        $mockOldPixelEverIdCookie .= ';111111111111111|2155991359353080227#2157830383339928168';
        $mockOldPixelEverIdCookie .= ';100000020686800|2155991359000202180#2156076321300417449';
        $mockOldPixelEverId = '2155991359353080227';
        $cookie = array_key_exists('wt3_eid', $_COOKIE) ? $_COOKIE['wt3_eid'] : '';
        $_COOKIE['wt3_eid'] = $mockOldPixelEverIdCookie;

        $queue = new MappIntelligenceQueue(array(
            'trackId' => '111111111111111',
            'trackDomain' => 'analytics01.wt-eu02.net',
            'debug' => true
        ));

        $queue->add(array());

        $requests = MappIntelligenceUnitUtil::getQueue($queue);
        $this->assertEquals(1, count($requests));
        $this->assertRegExp('/^wt\?p=600,0,,,,,[0-9]{13},0,,\&/', $requests[0]);
        $this->assertRegExp('/\&eid=' . $mockOldPixelEverId . '/', $requests[0]);

        $_COOKIE['wt3_eid'] = $cookie;
    }

    public function testDefaultPageName()
    {
        $queue = new MappIntelligenceQueue(array(
            'trackId' => '111111111111111',
            'trackDomain' => 'analytics01.wt-eu02.net',
            'debug' => true
        ));

        $mockHTTPHost = 'sub.domain.tld';
        $mockRequestURI = '/path/to/page.html';

        $_SERVER['HTTP_HOST'] = $mockHTTPHost;
        $_SERVER['REQUEST_URI'] = $mockRequestURI;

        $queue->add(array());

        $requests = MappIntelligenceUnitUtil::getQueue($queue);
        $this->assertEquals(1, count($requests));
        $this->assertRegExp(
            '/^wt\?p=600,' . preg_quote(rawurlencode($mockHTTPHost . $mockRequestURI)) . ',,,,,[0-9]{13},0,,\&/',
            $requests[0]
        );
        $this->assertRegExp(
            '/\&pu=' . preg_quote(rawurlencode('https://' . $mockHTTPHost . $mockRequestURI)) . '/',
            $requests[0]
        );
    }

    public function testDefaultPageNameWithoutParams()
    {
        $queue = new MappIntelligenceQueue(array(
            'trackId' => '111111111111111',
            'trackDomain' => 'analytics01.wt-eu02.net',
            'debug' => true,
            'useParamsForDefaultPageName' => array(
                'aa', 'bb', 'cc'
            )
        ));

        $mockHTTPHost = 'sub.domain.tld';
        $mockRequestURI = '/path/to/page.html';

        $_SERVER['HTTP_HOST'] = $mockHTTPHost;
        $_SERVER['REQUEST_URI'] = $mockRequestURI;

        $queue->add(array());

        $requests = MappIntelligenceUnitUtil::getQueue($queue);
        $this->assertEquals(1, count($requests));
        $this->assertRegExp(
            '/^wt\?p=600,' . preg_quote(rawurlencode($mockHTTPHost . $mockRequestURI)) . ',,,,,[0-9]{13},0,,\&/',
            $requests[0]
        );
        $this->assertRegExp(
            '/\&pu=' . preg_quote(rawurlencode('https://' . $mockHTTPHost . $mockRequestURI)) . '/',
            $requests[0]
        );
    }

    public function testDefaultPageNameWithParams()
    {
        $queue = new MappIntelligenceQueue(array(
            'trackId' => '111111111111111',
            'trackDomain' => 'analytics01.wt-eu02.net',
            'debug' => true,
            'useParamsForDefaultPageName' => array(
                'aa', 'bb', 'cc'
            )
        ));

        $mockHTTPHost = 'sub.domain.tld';
        $mockRequestURI = '/path/to/page.html';

        $_SERVER['HTTP_HOST'] = $mockHTTPHost;
        $_SERVER['REQUEST_URI'] = $mockRequestURI . '?bb=value%20bb&cc=value%20cc';
        $_GET['bb'] = 'value bb';
        $_GET['cc'] = 'value cc';

        $queue->add(array());

        $contentId = $mockHTTPHost . $mockRequestURI . '?bb=value bb&cc=value cc';

        $requests = MappIntelligenceUnitUtil::getQueue($queue);
        $this->assertEquals(1, count($requests));
        $this->assertRegExp(
            '/^wt\?p=600,' . preg_quote(rawurlencode($contentId)) . ',,,,,[0-9]{13},0,,\&/',
            $requests[0]
        );
        $this->assertRegExp(
            '/\&pu=' . preg_quote(rawurlencode('https://' . $mockHTTPHost . $mockRequestURI)) . '/',
            $requests[0]
        );
    }

    public function testEmptyReferrer()
    {
        $queue = new MappIntelligenceQueue(array(
            'trackId' => '111111111111111',
            'trackDomain' => 'analytics01.wt-eu02.net',
            'debug' => true
        ));

        $queue->add(array());

        $requests = MappIntelligenceUnitUtil::getQueue($queue);
        $this->assertEquals(1, count($requests));
        $this->assertRegExp('/^wt\?p=600,0,,,,,[0-9]{13},0,,\&/', $requests[0]);
    }

    public function testReferrerNotEqualsOwnDomain()
    {
        $queue = new MappIntelligenceQueue(array(
            'trackId' => '111111111111111',
            'trackDomain' => 'analytics01.wt-eu02.net',
            'debug' => true
        ));

        $mockReferrer = 'https://sub.domain.tld/path/to/previous/page.html';
        $_SERVER['HTTP_REFERER'] = $mockReferrer;

        $queue->add(array());

        $requests = MappIntelligenceUnitUtil::getQueue($queue);
        $this->assertEquals(1, count($requests));
        $this->assertRegExp(
            '/^wt\?p=600,0,,,,,[0-9]{13},' . preg_quote(rawurlencode($mockReferrer)) . ',,\&/',
            $requests[0]
        );
    }

    public function testInvalidReferrer()
    {
        $queue = new MappIntelligenceQueue(array(
            'trackId' => '111111111111111',
            'trackDomain' => 'analytics01.wt-eu02.net',
            'domain' => array('sub.domain.tld'),
            'debug' => true
        ));

        $mockReferrer = 'foo.bar';
        $_SERVER['HTTP_REFERER'] = $mockReferrer;

        $queue->add(array());

        $requests = MappIntelligenceUnitUtil::getQueue($queue);
        $this->assertEquals(1, count($requests));
        $this->assertRegExp(
            '/^wt\?p=600,0,,,,,[0-9]{13},' . preg_quote(rawurlencode($mockReferrer)) . ',,\&/',
            $requests[0]
        );
    }

    public function testReferrerEqualsOwnDomain1()
    {
        $queue = new MappIntelligenceQueue(array(
            'trackId' => '111111111111111',
            'trackDomain' => 'analytics01.wt-eu02.net',
            'domain' => array('sub.domain.tld'),
            'debug' => true
        ));

        $mockReferrer = 'https://sub.domain.tld/path/to/previous/page.html';
        $_SERVER['HTTP_REFERER'] = $mockReferrer;

        $queue->add(array());

        $requests = MappIntelligenceUnitUtil::getQueue($queue);
        $this->assertEquals(1, count($requests));
        $this->assertRegExp('/^wt\?p=600,0,,,,,[0-9]{13},1,,\&/', $requests[0]);
    }

    public function testReferrerEqualsOwnDomain2()
    {
        $queue = new MappIntelligenceQueue(array(
            'trackId' => '111111111111111',
            'trackDomain' => 'analytics01.wt-eu02.net',
            'domain' => array('/.+\.domain\.tld/'),
            'debug' => true
        ));

        $mockReferrer = 'https://sub.domain.tld/path/to/previous/page.html';
        $_SERVER['HTTP_REFERER'] = $mockReferrer;

        $queue->add(array());

        $requests = MappIntelligenceUnitUtil::getQueue($queue);
        $this->assertEquals(1, count($requests));
        $this->assertRegExp('/^wt\?p=600,0,,,,,[0-9]{13},1,,\&/', $requests[0]);
    }

    public function testReferrerEqualsOwnDomain3()
    {
        $queue = new MappIntelligenceQueue(array(
            'trackId' => '111111111111111',
            'trackDomain' => 'analytics01.wt-eu02.net',
            'domain' => array('/[a-z]{3}\.domain\.tld)/'),
            'debug' => true
        ));

        $mockReferrer = 'https://sub.domain.tld/path/to/previous/page.html';
        $_SERVER['HTTP_REFERER'] = $mockReferrer;

        $queue->add(array());

        $requests = MappIntelligenceUnitUtil::getQueue($queue);
        $this->assertEquals(1, count($requests));
        $this->assertRegExp(
            '/^wt\?p=600,0,,,,,[0-9]{13},' . preg_quote(rawurlencode($mockReferrer)) . ',,\&/',
            $requests[0]
        );
    }

    /**
     * flush
     */
    public function testFlushEmptyQueue()
    {
        $queue = new MappIntelligenceQueue(array());
        $this->assertEquals(true, $queue->flush());
    }

    public function testFlushEmptyQueueWithDebug()
    {
        $queue = new MappIntelligenceQueue(array(
            'debug' => true
        ));

        $this->assertEquals(true, $queue->flush());

        $fileContent = MappIntelligenceUnitUtil::getErrorLog();
        $this->assertContains('Sent batch requests, current queue size is 0 req.', $fileContent[0]);
        $this->assertContains('MappIntelligenceQueue is empty', $fileContent[1]);
    }

    public function testFlushQueueFailed()
    {
        $queue = new MappIntelligenceQueue(array(
            'trackId' => '111111111111111',
            'trackDomain' => 'analytics01.wt-eu02.net',
            'debug' => true
        ));

        $queue->add('wt?p=300,0');
        $this->assertEquals(false, $queue->flush());

        $fileContent = MappIntelligenceUnitUtil::getErrorLog();
        $this->assertContains('Sent batch requests, current queue size is 1 req.', $fileContent[1]);
        $this->assertContains(
            'Send batch data via cURL call to https://analytics01.wt-eu02.net/111111111111111/batch (1 req.)',
            $fileContent[2]
        );
        $this->assertContains('Batch request responding the status code 404', $fileContent[3]);
        $this->assertContains('[0]:', $fileContent[4]);
        $this->assertContains('Batch request failed!', $fileContent[5]);
        $this->assertContains('Batch of 1 req. sent, current queue size is 1 req.', $fileContent[6]);
    }

    public function testFlushQueueSuccess()
    {
        $queue = new MappIntelligenceQueue(array(
            'trackId' => '123451234512345',
            'trackDomain' => 'analytics01.wt-eu02.net',
            'debug' => true
        ));

        $queue->add('wt?p=300,0');
        $queue->add('wt?p=300,0');
        $queue->add('wt?p=300,0');
        $queue->add('wt?p=300,0');
        $queue->add('wt?p=300,0');
        $this->assertEquals(true, $queue->flush());

        $fileContent = MappIntelligenceUnitUtil::getErrorLog();
        $this->assertContains('Sent batch requests, current queue size is 5 req.', $fileContent[5]);
        $this->assertContains(
            'Send batch data via cURL call to https://analytics01.wt-eu02.net/123451234512345/batch (5 req.)',
            $fileContent[6]
        );
        $this->assertContains('Batch request responding the status code 200', $fileContent[7]);
        $this->assertContains('Batch of 5 req. sent, current queue size is 0 req.', $fileContent[8]);
        $this->assertContains('MappIntelligenceQueue is empty', $fileContent[9]);
    }
}
