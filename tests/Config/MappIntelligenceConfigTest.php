<?php

require_once __DIR__ . '/../MappIntelligenceExtendsTestCase.php';

/**
 * Class MappIntelligenceConfigTest
 */
class MappIntelligenceConfigTest extends MappIntelligenceExtendsTestCase
{
    public function testDefaultConfig()
    {
        $mappIntelligenceConfig = new MappIntelligenceConfig();

        $config = $mappIntelligenceConfig->build();
        $this->assertTrue(empty($config['trackId']));
        $this->assertTrue(empty($config['trackDomain']));
        $this->assertEquals(0, count($config['domain']));
        $this->assertTrue($config['logger'] instanceof MappIntelligenceDebugLogger);
        $this->assertEquals(MappIntelligenceConsumerType::CURL, $config['consumerType']);
        $this->assertNull($config['consumer']);
        $this->assertEquals(1, $config['maxAttempt']);
        $this->assertEquals(100, $config['attemptTimeout']);
        $this->assertEquals(50, $config['maxBatchSize']);
        $this->assertEquals(1000, $config['maxQueueSize']);
        $this->assertEquals(10 * 1000, $config['maxFileLines']);
        $this->assertEquals(30 * 60 * 1000, $config['maxFileDuration']);
        $this->assertEquals(24 * 1024 * 1024, $config['maxFileSize']);
        $this->assertEquals(true, $config['forceSSL']);
        $this->assertEquals(0, count($config['useParamsForDefaultPageName']));
        $this->assertTrue(empty($config['userAgent']));
        $this->assertTrue(empty($config['remoteAddress']));
        $this->assertTrue(empty($config['referrerURL']));
        $this->assertEquals(0, count($config['requestURL']));
        $this->assertEquals(sys_get_temp_dir() . '/', $config['filePath']);
        $this->assertEquals('MappIntelligenceRequests', $config['filePrefix']);
        $this->assertEquals(sys_get_temp_dir() . '/MappIntelligenceRequests.log', $config['filename']);
        $this->assertEquals('a', $config['fileMode']);
    }

    public function testSelfConfig()
    {
        $mic = new MappIntelligenceConfig();

        $config = (new MappIntelligenceConfig($mic))->build();
        $this->assertTrue(empty($config['trackId']));
        $this->assertTrue(empty($config['trackDomain']));
        $this->assertEquals(0, count($config['domain']));
        $this->assertTrue($config['logger'] instanceof MappIntelligenceDebugLogger);
        $this->assertEquals(MappIntelligenceConsumerType::CURL, $config['consumerType']);
        $this->assertNull($config['consumer']);
        $this->assertEquals(1, $config['maxAttempt']);
        $this->assertEquals(100, $config['attemptTimeout']);
        $this->assertEquals(50, $config['maxBatchSize']);
        $this->assertEquals(1000, $config['maxQueueSize']);
        $this->assertEquals(10 * 1000, $config['maxFileLines']);
        $this->assertEquals(30 * 60 * 1000, $config['maxFileDuration']);
        $this->assertEquals(24 * 1024 * 1024, $config['maxFileSize']);
        $this->assertEquals(true, $config['forceSSL']);
        $this->assertEquals(0, count($config['useParamsForDefaultPageName']));
        $this->assertTrue(empty($config['userAgent']));
        $this->assertTrue(empty($config['remoteAddress']));
        $this->assertTrue(empty($config['referrerURL']));
        $this->assertEquals(0, count($config['requestURL']));
        $this->assertEquals(sys_get_temp_dir() . '/', $config['filePath']);
        $this->assertEquals('MappIntelligenceRequests', $config['filePrefix']);
        $this->assertEquals(sys_get_temp_dir() . '/MappIntelligenceRequests.log', $config['filename']);
        $this->assertEquals('a', $config['fileMode']);
    }

    public function testNullConfig()
    {
        $mappIntelligenceConfig = new MappIntelligenceConfig(null);
        $mappIntelligenceConfig
            ->setTrackId(null)
            ->setTrackDomain(null)
            ->setUserAgent(null)
            ->setRemoteAddress(null)
            ->setReferrerURL(null)
            ->setRequestURL(null)
            ->setCookie(null)->addCookie(null, null)->addCookie("", null)->addCookie(null, "")
            ->setDomain(null)->addDomain(null)
            ->setLogger(null)
            ->setConsumerType(null)
            ->setConsumer(null)
            ->setFilePath(null)
            ->setFilePrefix(null)
            ->setUseParamsForDefaultPageName(null)->addUseParamsForDefaultPageName(null);

        $config = $mappIntelligenceConfig->build();
        $this->assertTrue(empty($config['trackId']));
        $this->assertTrue(empty($config['trackDomain']));
        $this->assertEquals(0, count($config['domain']));
        $this->assertTrue($config['logger'] instanceof MappIntelligenceDebugLogger);
        $this->assertEquals(MappIntelligenceConsumerType::CURL, $config['consumerType']);
        $this->assertNull($config['consumer']);
        $this->assertEquals(1, $config['maxAttempt']);
        $this->assertEquals(100, $config['attemptTimeout']);
        $this->assertEquals(50, $config['maxBatchSize']);
        $this->assertEquals(1000, $config['maxQueueSize']);
        $this->assertEquals(10 * 1000, $config['maxFileLines']);
        $this->assertEquals(30 * 60 * 1000, $config['maxFileDuration']);
        $this->assertEquals(24 * 1024 * 1024, $config['maxFileSize']);
        $this->assertEquals(true, $config['forceSSL']);
        $this->assertEquals(0, count($config['useParamsForDefaultPageName']));
        $this->assertTrue(empty($config['userAgent']));
        $this->assertTrue(empty($config['remoteAddress']));
        $this->assertTrue(empty($config['referrerURL']));
        $this->assertEquals(0, count($config['requestURL']));
        $this->assertEquals(sys_get_temp_dir() . '/', $config['filePath']);
        $this->assertEquals('MappIntelligenceRequests', $config['filePrefix']);
        $this->assertEquals(sys_get_temp_dir() . '/MappIntelligenceRequests.log', $config['filename']);
        $this->assertEquals('a', $config['fileMode']);
    }

    public function testConfigFile()
    {
        $mappIntelligenceConfig = new MappIntelligenceConfig(array(
            'config' => './config.ini'
        ));

        $config = $mappIntelligenceConfig->build();
        $this->assertEquals('', $config['trackId']);
        $this->assertEquals('', $config['trackDomain']);
        $this->assertEquals(0, count($config['domain']));
        $this->assertEquals(MappIntelligenceConsumerType::CURL, $config['consumerType']);
        $this->assertEquals(null, $config['consumer']);
        $this->assertEquals(sys_get_temp_dir() . '/MappIntelligenceRequests.log', $config['filename']);
        $this->assertEquals(1, $config['maxAttempt']);
        $this->assertEquals(100, $config['attemptTimeout']);
        $this->assertEquals(50, $config['maxBatchSize']);
        $this->assertEquals(1000, $config['maxQueueSize']);
        $this->assertEquals(true, $config['forceSSL']);
        $this->assertEquals(0, count($config['useParamsForDefaultPageName']));
    }

    public function testInvalidConfigFile()
    {
        $mappIntelligenceConfig = new MappIntelligenceConfig(__DIR__ . '/../../config.ini');

        $config = $mappIntelligenceConfig->build();
        $this->assertEquals('', $config['trackId']);
        $this->assertEquals('', $config['trackDomain']);
        $this->assertEquals(0, count($config['domain']));
        $this->assertEquals(MappIntelligenceConsumerType::CURL, $config['consumerType']);
        $this->assertEquals(null, $config['consumer']);
        $this->assertEquals(sys_get_temp_dir() . '/MappIntelligenceRequests.log', $config['filename']);
        $this->assertEquals('a', $config['fileMode']);
        $this->assertEquals(1, $config['maxAttempt']);
        $this->assertEquals(100, $config['attemptTimeout']);
        $this->assertEquals(50, $config['maxBatchSize']);
        $this->assertEquals(1000, $config['maxQueueSize']);
        $this->assertEquals(true, $config['forceSSL']);
        $this->assertEquals(0, count($config['useParamsForDefaultPageName']));
    }

    public function testOverwriteConfigFile()
    {
        $mappIntelligenceConfig = new MappIntelligenceConfig(array(
            'config' => './config.ini',
            'trackId' => '111111111111111',
            'trackDomain' => 'analytics01.wt-eu02.net',
            'maxAttempt' => 3,
            'attemptTimeout' => 200,
            'maxBatchSize' => 1000,
            'maxQueueSize' => 100000
        ));

        $config = $mappIntelligenceConfig->build();
        $this->assertEquals('111111111111111', $config['trackId']);
        $this->assertEquals('analytics01.wt-eu02.net', $config['trackDomain']);
        $this->assertEquals(0, count($config['domain']));
        $this->assertEquals(MappIntelligenceConsumerType::CURL, $config['consumerType']);
        $this->assertEquals(null, $config['consumer']);
        $this->assertEquals(sys_get_temp_dir() . '/MappIntelligenceRequests.log', $config['filename']);
        $this->assertEquals(3, $config['maxAttempt']);
        $this->assertEquals(200, $config['attemptTimeout']);
        $this->assertEquals(1000, $config['maxBatchSize']);
        $this->assertEquals(100000, $config['maxQueueSize']);
        $this->assertEquals(true, $config['forceSSL']);
        $this->assertEquals(0, count($config['useParamsForDefaultPageName']));
    }

    public function testConfigWithTrackIdAndTrackDomain()
    {
        $mappIntelligenceConfig = new MappIntelligenceConfig(array(
            'trackId' => '111111111111111',
            'trackDomain' => 'analytics01.wt-eu02.net'
        ));

        $config = $mappIntelligenceConfig->build();
        $this->assertEquals('111111111111111', $config['trackId']);
        $this->assertEquals('analytics01.wt-eu02.net', $config['trackDomain']);
    }

    public function testConfigWithDomain()
    {
        $mappIntelligenceConfig = new MappIntelligenceConfig(array(
            'domain' => array(
                'foo.bar.com', 'www.mappIntelligence.com', 'sub.domain.tld'
            )
        ));

        $config = $mappIntelligenceConfig->build();
        $this->assertEquals('foo.bar.com', $config['domain'][0]);
        $this->assertEquals('www.mappIntelligence.com', $config['domain'][1]);
        $this->assertEquals('sub.domain.tld', $config['domain'][2]);
    }

    public function testConfigWithFile()
    {
        $mappIntelligenceConfig = new MappIntelligenceConfig(array(
            'consumer' => 'file',
            'filename' => '/dev/null'
        ));

        $config = $mappIntelligenceConfig->build();
        $this->assertEquals('/dev/null', $config['filename']);
        $this->assertEquals(MappIntelligenceConsumerType::FILE, $config['consumerType']);
        $this->assertEquals(null, $config['consumer']);
        $this->assertEquals(1, $config['maxBatchSize']);
    }

    public function testHeaderData()
    {
        $mappIntelligenceConfig = new MappIntelligenceConfig();
        $mappIntelligenceConfig
            ->setUserAgent("Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:71.0) Gecko/20100101 Firefox/71.0")
            ->setRemoteAddress("127.0.0.1")
            ->setReferrerURL("https://sub.domain.tld/path/to/previous/page.html")
            ->setRequestURL("https://sub.domain.tld/path/to/page.html?foo=bar&test=123#abc");

        $config = $mappIntelligenceConfig->build();
        $this->assertEquals(
            "Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:71.0) Gecko/20100101 Firefox/71.0",
            $config["userAgent"]
        );
        $this->assertEquals("127.0.0.1", $config["remoteAddress"]);
        $this->assertEquals("https://sub.domain.tld/path/to/previous/page.html", $config["referrerURL"]);
        $this->assertEquals("sub.domain.tld", $config["domain"][0]);
    }

    public function testRequestURLInvalid()
    {
        $mappIntelligenceConfig = new MappIntelligenceConfig();
        $mappIntelligenceConfig->setRequestURL('sub.domain.tld:8080/path/to/page.html?foo=bar&test=123#abc');

        $config = $mappIntelligenceConfig->build();
        $this->assertEquals(0, count($config['domain']));
    }

    public function testOwnDomainWithPort80()
    {
        $mappIntelligenceConfig = new MappIntelligenceConfig();
        $mappIntelligenceConfig->setRequestURL('https://sub.domain.tld:80/path/to/page.html?foo=bar&test=123#abc');

        $config = $mappIntelligenceConfig->build();
        $this->assertEquals('sub.domain.tld', $config['domain'][0]);
    }

    public function testOwnDomainWithPort443()
    {
        $mappIntelligenceConfig = new MappIntelligenceConfig();
        $mappIntelligenceConfig->setRequestURL('https://sub.domain.tld:443/path/to/page.html?foo=bar&test=123#abc');

        $config = $mappIntelligenceConfig->build();
        $this->assertEquals('sub.domain.tld', $config['domain'][0]);
    }

    public function testOwnDomainWithPort8080()
    {
        $mappIntelligenceConfig = new MappIntelligenceConfig();
        $mappIntelligenceConfig->setRequestURL('https://sub.domain.tld:8080/path/to/page.html?foo=bar&test=123#abc');

        $config = $mappIntelligenceConfig->build();
        $this->assertEquals('sub.domain.tld:8080', $config['domain'][0]);
    }

    public function testInvalidMaxAttempt()
    {
        $mappIntelligenceConfig = new MappIntelligenceConfig(array(
            'maxAttempt' => 12
        ));

        $config = $mappIntelligenceConfig->build();
        $this->assertEquals(1, $config['maxAttempt']);
    }

    public function testInvalidMaxAttempt2()
    {
        $mappIntelligenceConfig = new MappIntelligenceConfig(array(
            'maxAttempt' => -12
        ));

        $config = $mappIntelligenceConfig->build();
        $this->assertEquals(1, $config['maxAttempt']);
    }

    public function testInvalidAttemptTimeout()
    {
        $mappIntelligenceConfig = new MappIntelligenceConfig(array(
            'attemptTimeout' => 750
        ));

        $config = $mappIntelligenceConfig->build();
        $this->assertEquals(100, $config['attemptTimeout']);
    }

    public function testInvalidAttemptTimeout2()
    {
        $mappIntelligenceConfig = new MappIntelligenceConfig(array(
            'attemptTimeout' => -750
        ));

        $config = $mappIntelligenceConfig->build();
        $this->assertEquals(100, $config['attemptTimeout']);
    }

    public function testInvalidMaxFileLines1()
    {
        $mappIntelligenceConfig = new MappIntelligenceConfig();
        $mappIntelligenceConfig->setMaxFileLines(11000);

        $config = $mappIntelligenceConfig->build();
        $this->assertEquals(10000, $config["maxFileLines"]);
    }

    public function testInvalidMaxFileLines2()
    {
        $mappIntelligenceConfig = new MappIntelligenceConfig();
        $mappIntelligenceConfig->setMaxFileLines(-1000);

        $config = $mappIntelligenceConfig->build();
        $this->assertEquals(10000, $config["maxFileLines"]);
    }

    public function testInvalidMaxFileDuration1()
    {
        $mappIntelligenceConfig = new MappIntelligenceConfig();
        $mappIntelligenceConfig->setMaxFileDuration(31 * 60 * 1000);

        $config = $mappIntelligenceConfig->build();
        $this->assertEquals(30 * 60 * 1000, $config["maxFileDuration"]);
    }

    public function testInvalidMaxFileDuration2()
    {
        $mappIntelligenceConfig = new MappIntelligenceConfig();
        $mappIntelligenceConfig->setMaxFileDuration(-30 * 1000);

        $config = $mappIntelligenceConfig->build();
        $this->assertEquals(30 * 60 * 1000, $config["maxFileDuration"]);
    }

    public function testInvalidMaxFileSize1()
    {
        $mappIntelligenceConfig = new MappIntelligenceConfig();
        $mappIntelligenceConfig->setMaxFileSize(25 * 1024 * 1024);

        $config = $mappIntelligenceConfig->build();
        $this->assertEquals(24 * 1024 * 1024, $config["maxFileSize"]);
    }

    public function testInvalidMaxFileSize2()
    {
        $mappIntelligenceConfig = new MappIntelligenceConfig();
        $mappIntelligenceConfig->setMaxFileSize(-24 * 1024);

        $config = $mappIntelligenceConfig->build();
        $this->assertEquals(24 * 1024 * 1024, $config["maxFileSize"]);
    }

    public function testInvalidFileMode()
    {
        $mappIntelligenceConfig = new MappIntelligenceConfig(array(
            'consumer' => 'file',
            'fileMode' => 'x'
        ));

        $config = $mappIntelligenceConfig->build();
        $this->assertEquals('a', $config['fileMode']);
    }

    public function testValidFileMode()
    {
        $mappIntelligenceConfig = new MappIntelligenceConfig(array(
            'consumer' => 'file',
            'fileMode' => 'c'
        ));

        $config = $mappIntelligenceConfig->build();
        $this->assertEquals('c', $config['fileMode']);
    }

    public function testAddUseParamsForDefaultPageName()
    {
        $mappIntelligenceConfig = new MappIntelligenceConfig();
        $mappIntelligenceConfig->setUseParamsForDefaultPageName(array('foo', 'bar'))
            ->addUseParamsForDefaultPageName('foo2')
            ->addUseParamsForDefaultPageName('bar2');

        $config = $mappIntelligenceConfig->build();
        $this->assertEquals('foo', $config['useParamsForDefaultPageName'][0]);
        $this->assertEquals('bar', $config['useParamsForDefaultPageName'][1]);
        $this->assertEquals('foo2', $config['useParamsForDefaultPageName'][2]);
        $this->assertEquals('bar2', $config['useParamsForDefaultPageName'][3]);
    }

    public function testCookie()
    {
        $mappIntelligenceConfig = (new MappIntelligenceConfig())
            ->setCookie(array())
            ->addCookie("foo", "bar")
            ->addCookie("test", "123")
            ->addCookie("abc", "cba");

        $config = $mappIntelligenceConfig->build();
        $this->assertEquals("bar", $config["cookie"]["foo"]);
        $this->assertEquals("123", $config["cookie"]["test"]);
        $this->assertEquals("cba", $config["cookie"]["abc"]);
    }
}
