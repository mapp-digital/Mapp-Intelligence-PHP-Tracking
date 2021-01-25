<?php

require_once __DIR__ . '/../MappIntelligenceExtendsTestCase.php';

class CustomConsumer implements MappIntelligenceConsumer
{
    public function sendBatch(array $batchContent)
    {
        // do nothing
    }
}

/**
 * Class MappIntelligenceQueueTestCase
 */
abstract class MappIntelligenceQueueTestCase extends MappIntelligenceExtendsTestCase
{
    /**
     * add
     */
    public function testAddTrackingRequest()
    {
        $c = new MappIntelligenceConfig(array(
            'trackId' => '111111111111111',
            'trackDomain' => 'analytics01.wt-eu02.net',
            'debug' => true
        ));
        $queue = new MappIntelligenceQueue($c->build());

        $queue->add('wt?p=300,0');

        $requests = MappIntelligenceUnitUtil::getQueue($queue);
        $this->assertEquals(1, count($requests));
        $this->assertEquals('wt?p=300,0', $requests[0]);
    }

    public function testAddEmptyTrackingData()
    {
        $c = new MappIntelligenceConfig(array(
            'trackId' => '111111111111111',
            'trackDomain' => 'analytics01.wt-eu02.net',
            'debug' => true
        ));
        $queue = new MappIntelligenceQueue($c->build());

        $queue->add(array());

        $requests = MappIntelligenceUnitUtil::getQueue($queue);
        $this->assertEquals(1, count($requests));
        $this->assertRegExpExtended('/^wt\?p=600,0,,,,,[0-9]{13},0,,\&$/', $requests[0]);
    }

    public function testMaxBatchSize()
    {
        $c = new MappIntelligenceConfig(array(
            'trackId' => '111111111111111',
            'trackDomain' => 'analytics01.wt-eu02.net',
            'debug' => true,
            'maxBatchSize' => 10
        ));
        $queue = new MappIntelligenceQueue($c->build());

        for ($i = 0; $i < 15; $i++) {
            $queue->add(array(
                'pn' => $i . ''
            ));
        }

        $fileContent = MappIntelligenceUnitUtil::getErrorLog();
        $this->assertContainsExtended('Sent batch requests, current queue size is 10 req.', $fileContent[10]);
        $this->assertContainsExtended('Sent batch requests, current queue size is 11 req.', $fileContent[17]);
        $this->assertContainsExtended('Sent batch requests, current queue size is 12 req.', $fileContent[24]);
        $this->assertContainsExtended('Sent batch requests, current queue size is 13 req.', $fileContent[31]);
        $this->assertContainsExtended('Sent batch requests, current queue size is 14 req.', $fileContent[38]);
        $this->assertContainsExtended('Sent batch requests, current queue size is 15 req.', $fileContent[45]);

        $requests = MappIntelligenceUnitUtil::getQueue($queue);
        $this->assertEquals(15, count($requests));
        for ($i = 0; $i < 15; $i++) {
            $this->assertRegExpExtended('/^wt\?p=600,' . $i . ',,,,,[0-9]{13},0,,\&$/', $requests[$i]);
        }
    }

    public function testWithUserAgent()
    {
        $mockUserAgent = 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:71.0) Gecko/20100101 Firefox/71.0';
        $_SERVER['HTTP_USER_AGENT'] = $mockUserAgent;

        $c = new MappIntelligenceConfig(array(
            'trackId' => '111111111111111',
            'trackDomain' => 'analytics01.wt-eu02.net',
            'debug' => true
        ));
        $queue = new MappIntelligenceQueue($c->build());

        $queue->add(array());

        $requests = MappIntelligenceUnitUtil::getQueue($queue);
        $this->assertEquals(1, count($requests));
        $this->assertRegExpExtended('/^wt\?p=600,0,,,,,[0-9]{13},0,,\&/', $requests[0]);
        $this->assertRegExpExtended('/\&X\-WT\-UA=' . preg_quote(rawurlencode($mockUserAgent)) . '/', $requests[0]);
    }

    public function testWithUserAgent2()
    {
        $mockUserAgent = 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:71.0) Gecko/20100101 Firefox/71.0';
        $_SERVER['HTTP_USER_AGENT'] = $mockUserAgent;

        $c = new MappIntelligenceConfig(array(
            'trackId' => '111111111111111',
            'trackDomain' => 'analytics01.wt-eu02.net',
            'debug' => true
        ));
        $queue = new MappIntelligenceQueue($c->build());

        $queue->add('wt?p=300,0');

        $requests = MappIntelligenceUnitUtil::getQueue($queue);
        $this->assertEquals(1, count($requests));
        $this->assertRegExpExtended('/^wt\?p=300,0\&/', $requests[0]);
        $this->assertRegExpExtended('/\&X\-WT\-UA=' . preg_quote(rawurlencode($mockUserAgent)) . '/', $requests[0]);
    }

    public function testWithUserAgent3()
    {
        $mockUserAgent = 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:71.0) Gecko/20100101 Firefox/71.0';
        $_SERVER['HTTP_USER_AGENT'] = $mockUserAgent;

        $c = new MappIntelligenceConfig(array(
            'trackId' => '111111111111111',
            'trackDomain' => 'analytics01.wt-eu02.net',
            'debug' => true
        ));
        $queue = new MappIntelligenceQueue($c->build());

        $queue->add('wt?p=300,0&X-WT-UA=test');

        $requests = MappIntelligenceUnitUtil::getQueue($queue);
        $this->assertEquals(1, count($requests));
        $this->assertRegExpExtended('/^wt\?p=300,0\&/', $requests[0]);
        $this->assertRegExpExtended('/\&X\-WT\-UA=test/', $requests[0]);
    }

    public function testWithRemoteAddr()
    {
        $mockRemoteAddr = '127.0.0.1';
        $_SERVER['REMOTE_ADDR'] = $mockRemoteAddr;

        $c = new MappIntelligenceConfig(array(
            'trackId' => '111111111111111',
            'trackDomain' => 'analytics01.wt-eu02.net',
            'debug' => true
        ));
        $queue = new MappIntelligenceQueue($c->build());

        $queue->add(array());

        $requests = MappIntelligenceUnitUtil::getQueue($queue);
        $this->assertEquals(1, count($requests));
        $this->assertRegExpExtended('/^wt\?p=600,0,,,,,[0-9]{13},0,,\&/', $requests[0]);
        $this->assertRegExpExtended('/\&X\-WT\-IP=' . preg_quote(rawurlencode($mockRemoteAddr)) . '/', $requests[0]);
    }

    public function testWithRemoteAddr2()
    {
        $mockRemoteAddr = '127.0.0.1';
        $_SERVER['REMOTE_ADDR'] = $mockRemoteAddr;

        $c = new MappIntelligenceConfig(array(
            'trackId' => '111111111111111',
            'trackDomain' => 'analytics01.wt-eu02.net',
            'debug' => true
        ));
        $queue = new MappIntelligenceQueue($c->build());

        $queue->add('wt?p=300,0');

        $requests = MappIntelligenceUnitUtil::getQueue($queue);
        $this->assertEquals(1, count($requests));
        $this->assertRegExpExtended('/^wt\?p=300,0\&/', $requests[0]);
        $this->assertRegExpExtended('/\&X\-WT\-IP=' . preg_quote(rawurlencode($mockRemoteAddr)) . '/', $requests[0]);
    }

    public function testWithRemoteAddr3()
    {
        $mockRemoteAddr = '127.0.0.1';
        $_SERVER['REMOTE_ADDR'] = $mockRemoteAddr;

        $c = new MappIntelligenceConfig(array(
            'trackId' => '111111111111111',
            'trackDomain' => 'analytics01.wt-eu02.net',
            'debug' => true
        ));
        $queue = new MappIntelligenceQueue($c->build());

        $queue->add('wt?p=300,0&X-WT-IP=127.0.0.20');

        $requests = MappIntelligenceUnitUtil::getQueue($queue);
        $this->assertEquals(1, count($requests));
        $this->assertRegExpExtended('/^wt\?p=300,0\&/', $requests[0]);
        $this->assertRegExpExtended('/\&X\-WT\-IP=127\.0\.0\.20/', $requests[0]);
    }

    public function testWithRequestURI()
    {
        $mockHTTPHost = 'sub.domain.tld';
        $mockRequestURI = '/path/to/page.html';

        $_SERVER['HTTP_HOST'] = $mockHTTPHost;
        $_SERVER['REQUEST_URI'] = $mockRequestURI;

        $c = new MappIntelligenceConfig(array(
            'trackId' => '111111111111111',
            'trackDomain' => 'analytics01.wt-eu02.net',
            'debug' => true
        ));
        $queue = new MappIntelligenceQueue($c->build());

        $queue->add(array());

        $requests = MappIntelligenceUnitUtil::getQueue($queue);
        $this->assertEquals(1, count($requests));
        $this->assertRegExpExtended(
            '/^wt\?p=600,' . preg_quote(rawurlencode($mockHTTPHost . $mockRequestURI)) . ',,,,,[0-9]{13},0,,\&/',
            $requests[0]
        );
        $this->assertRegExpExtended(
            '/\&pu=' . preg_quote(rawurlencode('https://' . $mockHTTPHost . $mockRequestURI)) . '/',
            $requests[0]
        );
    }

    public function testWithSmartPixelEverId()
    {
        $mockSmartPixelEverId = '2157070685656224066';
        $cookie = array_key_exists('wtstp_eid', $_COOKIE) ? $_COOKIE['wtstp_eid'] : '';
        $_COOKIE['wtstp_eid'] = $mockSmartPixelEverId;

        $c = new MappIntelligenceConfig(array(
            'trackId' => '111111111111111',
            'trackDomain' => 'analytics01.wt-eu02.net',
            'debug' => true
        ));
        $queue = new MappIntelligenceQueue($c->build());

        $queue->add(array());

        $requests = MappIntelligenceUnitUtil::getQueue($queue);
        $this->assertEquals(1, count($requests));
        $this->assertRegExpExtended('/^wt\?p=600,0,,,,,[0-9]{13},0,,\&/', $requests[0]);
        $this->assertRegExpExtended('/\&eid=' . $mockSmartPixelEverId . '/', $requests[0]);

        $_COOKIE['wtstp_eid'] = $cookie;
    }

    public function testWithTrackServerEverId()
    {
        $mockTrackServerEverId = '6157070685656224066';
        $cookie = array_key_exists('wteid_111111111111111', $_COOKIE) ? $_COOKIE['wteid_111111111111111'] : '';
        $_COOKIE['wteid_111111111111111'] = $mockTrackServerEverId;

        $c = new MappIntelligenceConfig(array(
            'trackId' => '111111111111111',
            'trackDomain' => 'analytics01.wt-eu02.net',
            'debug' => true
        ));
        $queue = new MappIntelligenceQueue($c->build());

        $queue->add(array());

        $requests = MappIntelligenceUnitUtil::getQueue($queue);
        $this->assertEquals(1, count($requests));
        $this->assertRegExpExtended('/^wt\?p=600,0,,,,,[0-9]{13},0,,\&/', $requests[0]);
        $this->assertRegExpExtended('/\&eid=' . $mockTrackServerEverId . '/', $requests[0]);

        $_COOKIE['wteid_111111111111111'] = $cookie;
    }

    public function testWithoutOldPixelEverId()
    {
        $mockOldPixelEverIdCookie = ';385255285199574|2155991359000202180#2157830383874881775';
        $mockOldPixelEverIdCookie .= ';222222222222222|2155991359353080227#2157830383339928168';
        $mockOldPixelEverIdCookie .= ';100000020686800|2155991359000202180#2156076321300417449';
        $cookie = array_key_exists('wt3_eid', $_COOKIE) ? $_COOKIE['wt3_eid'] : '';
        $_COOKIE['wt3_eid'] = $mockOldPixelEverIdCookie;

        $c = new MappIntelligenceConfig(array(
            'trackId' => '111111111111111',
            'trackDomain' => 'analytics01.wt-eu02.net',
            'debug' => true
        ));
        $queue = new MappIntelligenceQueue($c->build());

        $queue->add(array());

        $requests = MappIntelligenceUnitUtil::getQueue($queue);
        $this->assertEquals(1, count($requests));
        $this->assertRegExpExtended('/^wt\?p=600,0,,,,,[0-9]{13},0,,\&$/', $requests[0]);

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

        $c = new MappIntelligenceConfig(array(
            'trackId' => '111111111111111',
            'trackDomain' => 'analytics01.wt-eu02.net',
            'debug' => true
        ));
        $queue = new MappIntelligenceQueue($c->build());

        $queue->add(array());

        $requests = MappIntelligenceUnitUtil::getQueue($queue);
        $this->assertEquals(1, count($requests));
        $this->assertRegExpExtended('/^wt\?p=600,0,,,,,[0-9]{13},0,,\&/', $requests[0]);
        $this->assertRegExpExtended('/\&eid=' . $mockOldPixelEverId . '/', $requests[0]);

        $_COOKIE['wt3_eid'] = $cookie;
    }

    public function testDefaultPageName()
    {
        $mockHTTPHost = 'sub.domain.tld';
        $mockRequestURI = '/path/to/page.html';

        $_SERVER['HTTP_HOST'] = $mockHTTPHost;
        $_SERVER['REQUEST_URI'] = $mockRequestURI;

        $c = new MappIntelligenceConfig(array(
            'trackId' => '222222222222222',
            'trackDomain' => 'analytics01.wt-eu02.net',
            'debug' => true
        ));
        $queue = new MappIntelligenceQueue($c->build());

        $queue->add(array());

        $requests = MappIntelligenceUnitUtil::getQueue($queue);
        $this->assertEquals(1, count($requests));
        $this->assertRegExpExtended(
            '/^wt\?p=600,' . preg_quote(rawurlencode($mockHTTPHost . $mockRequestURI)) . ',,,,,[0-9]{13},0,,\&/',
            $requests[0]
        );
        $this->assertRegExpExtended(
            '/\&pu=' . preg_quote(rawurlencode('https://' . $mockHTTPHost . $mockRequestURI)) . '/',
            $requests[0]
        );
    }

    public function testDefaultPageNameWithoutParams()
    {
        $mockHTTPHost = 'sub.domain.tld';
        $mockRequestURI = '/path/to/page.html';

        $_SERVER['HTTP_HOST'] = $mockHTTPHost;
        $_SERVER['REQUEST_URI'] = $mockRequestURI;

        $c = new MappIntelligenceConfig(array(
            'trackId' => '111111111111111',
            'trackDomain' => 'analytics01.wt-eu02.net',
            'debug' => true,
            'useParamsForDefaultPageName' => array(
                'aa', 'bb', 'cc'
            )
        ));
        $queue = new MappIntelligenceQueue($c->build());

        $queue->add(array());

        $requests = MappIntelligenceUnitUtil::getQueue($queue);
        $this->assertEquals(1, count($requests));
        $this->assertRegExpExtended(
            '/^wt\?p=600,' . preg_quote(rawurlencode($mockHTTPHost . $mockRequestURI)) . ',,,,,[0-9]{13},0,,\&/',
            $requests[0]
        );
        $this->assertRegExpExtended(
            '/\&pu=' . preg_quote(rawurlencode('https://' . $mockHTTPHost . $mockRequestURI)) . '/',
            $requests[0]
        );
    }

    public function testDefaultPageNameWithParams()
    {
        $mockHTTPHost = 'sub.domain.tld';
        $mockRequestURI = '/path/to/page.html';

        $_SERVER['HTTP_HOST'] = $mockHTTPHost;
        $_SERVER['REQUEST_URI'] = $mockRequestURI . '?bb=value%20bb&cc=value%20cc';
        $_GET['bb'] = 'value bb';
        $_GET['cc'] = 'value cc';

        $c = new MappIntelligenceConfig(array(
            'trackId' => '111111111111111',
            'trackDomain' => 'analytics01.wt-eu02.net',
            'debug' => true,
            'useParamsForDefaultPageName' => array(
                'aa', 'bb', 'cc'
            )
        ));
        $queue = new MappIntelligenceQueue($c->build());

        $queue->add(array());

        $contentId = $mockHTTPHost . $mockRequestURI . '?bb=value bb&cc=value cc';

        $requests = MappIntelligenceUnitUtil::getQueue($queue);
        $this->assertEquals(1, count($requests));
        $this->assertRegExpExtended(
            '/^wt\?p=600,' . preg_quote(rawurlencode($contentId)) . ',,,,,[0-9]{13},0,,\&/',
            $requests[0]
        );
        $this->assertRegExpExtended(
            '/\&pu=' . preg_quote(rawurlencode('https://' . $mockHTTPHost . $mockRequestURI)) . '/',
            $requests[0]
        );
    }

    public function testEmptyReferrer()
    {
        $c = new MappIntelligenceConfig(array(
            'trackId' => '111111111111111',
            'trackDomain' => 'analytics01.wt-eu02.net',
            'debug' => true
        ));
        $queue = new MappIntelligenceQueue($c->build());

        $queue->add(array());

        $requests = MappIntelligenceUnitUtil::getQueue($queue);
        $this->assertEquals(1, count($requests));
        $this->assertRegExpExtended('/^wt\?p=600,0,,,,,[0-9]{13},0,,\&/', $requests[0]);
    }

    public function testReferrerNotEqualsOwnDomain()
    {
        $mockReferrer = 'https://sub.domain.tld/path/to/previous/page.html';
        $_SERVER['HTTP_REFERER'] = $mockReferrer;

        $c = new MappIntelligenceConfig(array(
            'trackId' => '111111111111111',
            'trackDomain' => 'analytics01.wt-eu02.net',
            'debug' => true
        ));
        $queue = new MappIntelligenceQueue($c->build());

        $queue->add(array());

        $requests = MappIntelligenceUnitUtil::getQueue($queue);
        $this->assertEquals(1, count($requests));
        $this->assertRegExpExtended(
            '/^wt\?p=600,0,,,,,[0-9]{13},' . preg_quote(rawurlencode($mockReferrer)) . ',,\&/',
            $requests[0]
        );
    }

    public function testInvalidReferrer()
    {
        $mockReferrer = 'foo.bar';
        $_SERVER['HTTP_REFERER'] = $mockReferrer;

        $c = new MappIntelligenceConfig(array(
            'trackId' => '111111111111111',
            'trackDomain' => 'analytics01.wt-eu02.net',
            'domain' => array('sub.domain.tld'),
            'debug' => true
        ));
        $queue = new MappIntelligenceQueue($c->build());

        $queue->add(array());

        $requests = MappIntelligenceUnitUtil::getQueue($queue);
        $this->assertEquals(1, count($requests));
        $this->assertRegExpExtended(
            '/^wt\?p=600,0,,,,,[0-9]{13},' . preg_quote(rawurlencode($mockReferrer)) . ',,\&/',
            $requests[0]
        );
    }

    public function testReferrerEqualsOwnDomain1()
    {
        $mockReferrer = 'https://sub.domain.tld/path/to/previous/page.html';
        $_SERVER['HTTP_REFERER'] = $mockReferrer;

        $c = new MappIntelligenceConfig(array(
            'trackId' => '111111111111111',
            'trackDomain' => 'analytics01.wt-eu02.net',
            'domain' => array('sub.domain.tld'),
            'debug' => true
        ));
        $queue = new MappIntelligenceQueue($c->build());

        $queue->add(array());

        $requests = MappIntelligenceUnitUtil::getQueue($queue);
        $this->assertEquals(1, count($requests));
        $this->assertRegExpExtended('/^wt\?p=600,0,,,,,[0-9]{13},1,,\&/', $requests[0]);
    }

    public function testReferrerEqualsOwnDomain2()
    {
        $mockReferrer = 'https://sub.domain.tld/path/to/previous/page.html';
        $_SERVER['HTTP_REFERER'] = $mockReferrer;

        $c = new MappIntelligenceConfig(array(
            'trackId' => '111111111111111',
            'trackDomain' => 'analytics01.wt-eu02.net',
            'domain' => array('/.+\.domain\.tld/'),
            'debug' => true
        ));
        $queue = new MappIntelligenceQueue($c->build());

        $queue->add(array());

        $requests = MappIntelligenceUnitUtil::getQueue($queue);
        $this->assertEquals(1, count($requests));
        $this->assertRegExpExtended('/^wt\?p=600,0,,,,,[0-9]{13},1,,\&/', $requests[0]);
    }

    public function testReferrerEqualsOwnDomain3()
    {
        $mockReferrer = 'https://sub.domain.tld/path/to/previous/page.html';
        $_SERVER['HTTP_REFERER'] = $mockReferrer;

        $c = new MappIntelligenceConfig(array(
            'trackId' => '111111111111111',
            'trackDomain' => 'analytics01.wt-eu02.net',
            'domain' => array('/[a-z]{3}\.domain\.tld)/'),
            'debug' => true
        ));
        $queue = new MappIntelligenceQueue($c->build());

        $queue->add(array());

        $requests = MappIntelligenceUnitUtil::getQueue($queue);
        $this->assertEquals(1, count($requests));
        $this->assertRegExpExtended(
            '/^wt\?p=600,0,,,,,[0-9]{13},' . preg_quote(rawurlencode($mockReferrer)) . ',,\&/',
            $requests[0]
        );
    }

    /**
     * consumer
     */
    public function testConsumerTypeCurl()
    {
        $c = new MappIntelligenceConfig(array(
            'consumerType' => MappIntelligenceConsumerType::CURL
        ));
        $queue = new MappIntelligenceQueue($c->build());

        $consumer = MappIntelligenceUnitHelper::getProperty($queue, 'consumer');
        $this->assertTrue($consumer instanceof MappIntelligenceConsumerCurl);
    }

    public function testConsumerTypeForkCurl()
    {
        $c = new MappIntelligenceConfig(array(
            'consumerType' => MappIntelligenceConsumerType::FORK_CURL
        ));
        $queue = new MappIntelligenceQueue($c->build());

        $consumer = MappIntelligenceUnitHelper::getProperty($queue, 'consumer');
        $this->assertTrue($consumer instanceof MappIntelligenceConsumerForkCurl);
    }

    public function testConsumerTypeFile()
    {
        $c = new MappIntelligenceConfig(array(
            'consumerType' => MappIntelligenceConsumerType::FILE
        ));
        $queue = new MappIntelligenceQueue($c->build());

        $consumer = MappIntelligenceUnitHelper::getProperty($queue, 'consumer');
        $this->assertTrue($consumer instanceof MappIntelligenceConsumerFile);
    }

    public function testConsumerTypeFileRotation()
    {
        $c = new MappIntelligenceConfig(array(
            'consumerType' => MappIntelligenceConsumerType::FILE_ROTATION
        ));
        $queue = new MappIntelligenceQueue($c->build());

        $consumer = MappIntelligenceUnitHelper::getProperty($queue, 'consumer');
        $this->assertTrue($consumer instanceof MappIntelligenceConsumerFileRotation);
    }

    public function testConsumerTypeCustom()
    {
        $c = new MappIntelligenceConfig(array(
            'consumerType' => MappIntelligenceConsumerType::CUSTOM,
            'consumer' => new CustomConsumer()
        ));
        $queue = new MappIntelligenceQueue($c->build());

        $consumer = MappIntelligenceUnitHelper::getProperty($queue, 'consumer');
        $this->assertTrue($consumer instanceof CustomConsumer);
    }

    /**
     * flush
     */
    public function testFlushEmptyQueue()
    {
        $c = new MappIntelligenceConfig();
        $queue = new MappIntelligenceQueue($c->build());
        $this->assertEquals(true, $queue->flush());
    }

    public function testFlushEmptyQueueWithDebug()
    {
        $c = new MappIntelligenceConfig(array(
            'debug' => true
        ));
        $queue = new MappIntelligenceQueue($c->build());

        $this->assertEquals(true, $queue->flush());

        $fileContent = MappIntelligenceUnitUtil::getErrorLog();
        $this->assertContainsExtended('Sent batch requests, current queue size is 0 req.', $fileContent[0]);
        $this->assertContainsExtended('MappIntelligenceQueue is empty', $fileContent[1]);
    }

    public function testFlushQueueFailed()
    {
        $c = new MappIntelligenceConfig(array(
            'trackId' => '111111111111111',
            'trackDomain' => 'analytics01.wt-eu02.net',
            'debug' => true
        ));
        $queue = new MappIntelligenceQueue($c->build());

        $queue->add('wt?p=300,0');
        $this->assertEquals(false, $queue->flush());

        $fileContent = MappIntelligenceUnitUtil::getErrorLog();
        $this->assertContainsExtended('Sent batch requests, current queue size is 1 req.', $fileContent[1]);
        $this->assertContainsExtended(
            'Send batch data to https://analytics01.wt-eu02.net/111111111111111/batch (1 req.)',
            $fileContent[2]
        );
        $this->assertContainsExtended('Batch request responding the status code 404', $fileContent[3]);
        $this->assertContainsExtended('[0]:', $fileContent[4]);
        $this->assertContainsExtended('Batch request failed!', $fileContent[5]);
        $this->assertContainsExtended('Batch of 1 req. sent, current queue size is 1 req.', $fileContent[6]);
    }

    public function testFlushQueueSuccess()
    {
        $c = new MappIntelligenceConfig(array(
            'trackId' => '123451234512345',
            'trackDomain' => 'analytics01.wt-eu02.net',
            'debug' => true
        ));
        $queue = new MappIntelligenceQueue($c->build());

        $queue->add('wt?p=300,0');
        $queue->add('wt?p=300,0');
        $queue->add('wt?p=300,0');
        $queue->add('wt?p=300,0');
        $queue->add('wt?p=300,0');
        $this->assertEquals(true, $queue->flush());

        $fileContent = MappIntelligenceUnitUtil::getErrorLog();
        $this->assertContainsExtended('Sent batch requests, current queue size is 5 req.', $fileContent[5]);
        $this->assertContainsExtended(
            'Send batch data to https://analytics01.wt-eu02.net/123451234512345/batch (5 req.)',
            $fileContent[6]
        );
        $this->assertContainsExtended('Batch request responding the status code 200', $fileContent[7]);
        $this->assertContainsExtended('Batch of 5 req. sent, current queue size is 0 req.', $fileContent[8]);
        $this->assertContainsExtended('MappIntelligenceQueue is empty', $fileContent[9]);
    }
}
