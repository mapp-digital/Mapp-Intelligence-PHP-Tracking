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
        $this->assertFalse($config['deactivate']);
        $this->assertFalse($config['deactivateByInAndExclude']);
        $this->assertEquals(0, count($config['containsInclude']));
        $this->assertEquals(0, count($config['containsExclude']));
        $this->assertEquals(0, count($config['matchesInclude']));
        $this->assertEquals(0, count($config['matchesExclude']));
        $this->assertEquals(14, $config['statistics']);
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
        $this->assertFalse($config['deactivate']);
        $this->assertFalse($config['deactivateByInAndExclude']);
        $this->assertEquals(0, count($config['containsInclude']));
        $this->assertEquals(0, count($config['containsExclude']));
        $this->assertEquals(0, count($config['matchesInclude']));
        $this->assertEquals(0, count($config['matchesExclude']));
        $this->assertEquals(14, $config['statistics']);
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
            ->setUseParamsForDefaultPageName(null)->addUseParamsForDefaultPageName(null)
            ->setContainsInclude(null)->addContainsInclude(null)
            ->setContainsExclude(null)->addContainsExclude(null)
            ->setMatchesInclude(null)->addMatchesInclude(null)
            ->setMatchesExclude(null)->addMatchesExclude(null);

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
        $this->assertFalse($config['deactivate']);
        $this->assertFalse($config['deactivateByInAndExclude']);
        $this->assertEquals(0, count($config['containsInclude']));
        $this->assertEquals(0, count($config['containsExclude']));
        $this->assertEquals(0, count($config['matchesInclude']));
        $this->assertEquals(0, count($config['matchesExclude']));
        $this->assertEquals(14, $config['statistics']);
    }

    public function testConfigFile()
    {
        $mappIntelligenceConfig = new MappIntelligenceConfig(array(
            'config' => __DIR__ . '/../_cfg/config.ini'
        ));

        $config = $mappIntelligenceConfig->build();
        $this->assertEquals('123451234512345', $config['trackId']);
        $this->assertEquals('analytics01.wt-eu02.net', $config['trackDomain']);
        $this->assertEquals(2, count($config['domain']));
        $this->assertEquals(MappIntelligenceConsumerType::FILE, $config['consumerType']);
        $this->assertEquals(null, $config['consumer']);
        $this->assertEquals(sys_get_temp_dir() . '/MappIntelligenceRequests.log', $config['filename']);
        $this->assertEquals(1, $config['maxAttempt']);
        $this->assertEquals(100, $config['attemptTimeout']);
        $this->assertEquals(1, $config['maxBatchSize']);
        $this->assertEquals(1000, $config['maxQueueSize']);
        $this->assertEquals(1000, $config['maxFileLines']);
        $this->assertEquals(180000, $config['maxFileDuration']);
        $this->assertEquals(24576, $config['maxFileSize']);
        $this->assertEquals(true, $config['forceSSL']);
        $this->assertEquals(3, count($config['useParamsForDefaultPageName']));
        $this->assertEquals(2, count($config['containsInclude']));
        $this->assertEquals(1, count($config['containsExclude']));
        $this->assertEquals(2, count($config['matchesInclude']));
        $this->assertEquals(1, count($config['matchesExclude']));
        $this->assertEquals(71, $config['statistics']);
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
        $this->assertEquals(0, count($config['containsInclude']));
        $this->assertEquals(0, count($config['containsExclude']));
        $this->assertEquals(0, count($config['matchesInclude']));
        $this->assertEquals(0, count($config['matchesExclude']));
        $this->assertEquals(14, $config['statistics']);
    }

    public function testOverwriteConfigFile()
    {
        $mappIntelligenceConfig = new MappIntelligenceConfig(array(
            'config' => __DIR__ . '/../_cfg/config.ini',
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
        $this->assertEquals(2, count($config['domain']));
        $this->assertEquals(MappIntelligenceConsumerType::FILE, $config['consumerType']);
        $this->assertEquals(null, $config['consumer']);
        $this->assertEquals(sys_get_temp_dir() . '/MappIntelligenceRequests.log', $config['filename']);
        $this->assertEquals(3, $config['maxAttempt']);
        $this->assertEquals(200, $config['attemptTimeout']);
        $this->assertEquals(1, $config['maxBatchSize']);
        $this->assertEquals(100000, $config['maxQueueSize']);
        $this->assertEquals(1000, $config['maxFileLines']);
        $this->assertEquals(180000, $config['maxFileDuration']);
        $this->assertEquals(24576, $config['maxFileSize']);
        $this->assertEquals(true, $config['forceSSL']);
        $this->assertEquals(3, count($config['useParamsForDefaultPageName']));
        $this->assertEquals(2, count($config['containsInclude']));
        $this->assertEquals(1, count($config['containsExclude']));
        $this->assertEquals(2, count($config['matchesInclude']));
        $this->assertEquals(1, count($config['matchesExclude']));
        $this->assertEquals(71, $config['statistics']);
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
        $this->assertEquals(70, $config['statistics']);
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
        $this->assertEquals(14, $config['statistics']);
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
        $this->assertEquals(70, $config['statistics']);
    }

    public function testValidFileMode()
    {
        $mappIntelligenceConfig = new MappIntelligenceConfig(array(
            'consumer' => 'file',
            'fileMode' => 'c'
        ));

        $config = $mappIntelligenceConfig->build();
        $this->assertEquals('c', $config['fileMode']);
        $this->assertEquals(70, $config['statistics']);
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
        $this->assertEquals(15, $config['statistics']);
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

    public function testConfigWithContainsInclude()
    {
        $mappIntelligenceConfig = new MappIntelligenceConfig();
        $mappIntelligenceConfig->setContainsInclude(array())
            ->addContainsInclude('foo.bar.com')
            ->addContainsInclude('www.mappIntelligence.com')
            ->addContainsInclude('sub.domain.tld');

        $config = $mappIntelligenceConfig->build();
        $this->assertEquals('foo.bar.com', $config['containsInclude'][0]);
        $this->assertEquals('www.mappIntelligence.com', $config['containsInclude'][1]);
        $this->assertEquals('sub.domain.tld', $config['containsInclude'][2]);
    }

    public function testConfigWithContainsExclude()
    {
        $mappIntelligenceConfig = new MappIntelligenceConfig();
        $mappIntelligenceConfig->setContainsExclude(array())
            ->addContainsExclude('foo.bar.com')
            ->addContainsExclude('www.mappIntelligence.com')
            ->addContainsExclude('sub.domain.tld');

        $config = $mappIntelligenceConfig->build();
        $this->assertEquals('foo.bar.com', $config['containsExclude'][0]);
        $this->assertEquals('www.mappIntelligence.com', $config['containsExclude'][1]);
        $this->assertEquals('sub.domain.tld', $config['containsExclude'][2]);
    }

    public function testConfigWithMatchesInclude()
    {
        $mappIntelligenceConfig = new MappIntelligenceConfig();
        $mappIntelligenceConfig->setMatchesInclude(array())
            ->addMatchesInclude('/foo\.bar\.com/')
            ->addMatchesInclude('/www\.mappIntelligence\.com/')
            ->addMatchesInclude('/sub\.domain\.tld/');

        $config = $mappIntelligenceConfig->build();
        $this->assertEquals('/foo\.bar\.com/', $config['matchesInclude'][0]);
        $this->assertEquals('/www\.mappIntelligence\.com/', $config['matchesInclude'][1]);
        $this->assertEquals('/sub\.domain\.tld/', $config['matchesInclude'][2]);
    }

    public function testConfigWithMatchesExclude()
    {
        $mappIntelligenceConfig = new MappIntelligenceConfig();
        $mappIntelligenceConfig->setMatchesExclude(array())
            ->addMatchesExclude('/foo\.bar\.com/')
            ->addMatchesExclude('/www\.mappIntelligence\.com/')
            ->addMatchesExclude('/sub\.domain\.tld/');

        $config = $mappIntelligenceConfig->build();
        $this->assertEquals('/foo\.bar\.com/', $config['matchesExclude'][0]);
        $this->assertEquals('/www\.mappIntelligence\.com/', $config['matchesExclude'][1]);
        $this->assertEquals('/sub\.domain\.tld/', $config['matchesExclude'][2]);
    }

    public function testRequestWithContainsInclude1()
    {
        $mappIntelligenceConfig = (new MappIntelligenceConfig())
            ->addContainsInclude('sub.domain.tld')
            ->setRequestURL('https://sub.domain.tld:80/path/to/page.html?foo=bar&test=123#abc');

        $config = $mappIntelligenceConfig->build();
        $this->assertEquals(false, $config['deactivateByInAndExclude']);
    }

    public function testRequestWithContainsInclude2()
    {
        $mappIntelligenceConfig = (new MappIntelligenceConfig())
            ->addContainsInclude('sub.domain1.tld')
            ->addContainsInclude('sub.domain2.tld')
            ->addContainsInclude('sub.domain3.tld')
            ->addContainsInclude('sub.domain.tld')
            ->setRequestURL('https://sub.domain.tld:80/path/to/page.html?foo=bar&test=123#abc');

        $config = $mappIntelligenceConfig->build();
        $this->assertEquals(false, $config['deactivateByInAndExclude']);
    }

    public function testRequestWithContainsInclude3()
    {
        $mappIntelligenceConfig = (new MappIntelligenceConfig())
            ->addContainsInclude('sub.domain1.tld')
            ->setRequestURL('https://sub.domain.tld:80/path/to/page.html?foo=bar&test=123#abc');

        $config = $mappIntelligenceConfig->build();
        $this->assertEquals(true, $config['deactivateByInAndExclude']);
    }

    public function testRequestWithMatchesInclude1()
    {
        $mappIntelligenceConfig = (new MappIntelligenceConfig())
            ->addMatchesInclude('/sub\.domain\.tld/')
            ->setRequestURL('https://sub.domain.tld:80/path/to/page.html?foo=bar&test=123#abc');

        $config = $mappIntelligenceConfig->build();
        $this->assertEquals(false, $config['deactivateByInAndExclude']);
    }

    public function testRequestWithMatchesInclude2()
    {
        $mappIntelligenceConfig = (new MappIntelligenceConfig())
            ->addMatchesInclude('/sub\.domain1\.tld/')
            ->addMatchesInclude('/sub\.domain2\.tld/')
            ->addMatchesInclude('/sub\.domain3\.tld/')
            ->addMatchesInclude('/sub\.domain5[\.tld/')
            ->addMatchesInclude('/sub\.domain\.tld/')
            ->setRequestURL('https://sub.domain.tld:80/path/to/page.html?foo=bar&test=123#abc');

        $config = $mappIntelligenceConfig->build();
        $this->assertEquals(false, $config['deactivateByInAndExclude']);
    }

    public function testRequestWithMatchesInclude3()
    {
        $mappIntelligenceConfig = (new MappIntelligenceConfig())
            ->addMatchesInclude('/sub\.domain1\.tld/')
            ->setRequestURL('https://sub.domain.tld:80/path/to/page.html?foo=bar&test=123#abc');

        $config = $mappIntelligenceConfig->build();
        $this->assertEquals(true, $config['deactivateByInAndExclude']);
    }

    public function testRequestWithContainsAndMatchesInclude1()
    {
        $mappIntelligenceConfig = (new MappIntelligenceConfig())
            ->addContainsInclude('sub.domain.tld')
            ->addMatchesInclude('/sub\.domain1\.tld/')
            ->setRequestURL('https://sub.domain.tld:80/path/to/page.html?foo=bar&test=123#abc');

        $config = $mappIntelligenceConfig->build();
        $this->assertEquals(false, $config['deactivateByInAndExclude']);
    }

    public function testRequestWithContainsAndMatchesInclude2()
    {
        $mappIntelligenceConfig = (new MappIntelligenceConfig())
            ->addContainsInclude('sub.domain1.tld')
            ->addMatchesInclude('/sub\.domain\.tld/')
            ->setRequestURL('https://sub.domain.tld:80/path/to/page.html?foo=bar&test=123#abc');

        $config = $mappIntelligenceConfig->build();
        $this->assertEquals(false, $config['deactivateByInAndExclude']);
    }

    public function testRequestWithContainsAndMatchesInclude3()
    {
        $mappIntelligenceConfig = (new MappIntelligenceConfig())
            ->addContainsInclude('sub.domain1.tld')
            ->addMatchesInclude('/sub\.domain1\.tld/')
            ->setRequestURL('https://sub.domain.tld:80/path/to/page.html?foo=bar&test=123#abc');

        $config = $mappIntelligenceConfig->build();
        $this->assertEquals(true, $config['deactivateByInAndExclude']);
    }

    public function testRequestWithContainsExclude1()
    {
        $mappIntelligenceConfig = (new MappIntelligenceConfig())
            ->addContainsExclude('sub.domain.tld')
            ->setRequestURL('https://sub.domain.tld:80/path/to/page.html?foo=bar&test=123#abc');

        $config = $mappIntelligenceConfig->build();
        $this->assertEquals(true, $config['deactivateByInAndExclude']);
    }

    public function testRequestWithContainsExclude2()
    {
        $mappIntelligenceConfig = (new MappIntelligenceConfig())
            ->addContainsExclude('sub.domain1.tld')
            ->addContainsExclude('sub.domain2.tld')
            ->addContainsExclude('sub.domain3.tld')
            ->addContainsExclude('sub.domain.tld')
            ->setRequestURL('https://sub.domain.tld:80/path/to/page.html?foo=bar&test=123#abc');

        $config = $mappIntelligenceConfig->build();
        $this->assertEquals(true, $config['deactivateByInAndExclude']);
    }

    public function testRequestWithContainsExclude3()
    {
        $mappIntelligenceConfig = (new MappIntelligenceConfig())
            ->addContainsExclude('sub.domain1.tld')
            ->setRequestURL('https://sub.domain.tld:80/path/to/page.html?foo=bar&test=123#abc');

        $config = $mappIntelligenceConfig->build();
        $this->assertEquals(false, $config['deactivateByInAndExclude']);
    }

    public function testRequestWithMatchesExclude1()
    {
        $mappIntelligenceConfig = (new MappIntelligenceConfig())
            ->addMatchesExclude('/sub\.domain\.tld/')
            ->setRequestURL('https://sub.domain.tld:80/path/to/page.html?foo=bar&test=123#abc');

        $config = $mappIntelligenceConfig->build();
        $this->assertEquals(true, $config['deactivateByInAndExclude']);
    }

    public function testRequestWithMatchesExclude2()
    {
        $mappIntelligenceConfig = (new MappIntelligenceConfig())
            ->addMatchesExclude('/sub\.domain1\.tld/')
            ->addMatchesExclude('/sub\.domain2\.tld/')
            ->addMatchesExclude('/sub\.domain3\.tld/')
            ->addMatchesExclude('/sub\.domain\.tld/')
            ->setRequestURL('https://sub.domain.tld:80/path/to/page.html?foo=bar&test=123#abc');

        $config = $mappIntelligenceConfig->build();
        $this->assertEquals(true, $config['deactivateByInAndExclude']);
    }

    public function testRequestWithMatchesExclude3()
    {
        $mappIntelligenceConfig = (new MappIntelligenceConfig())
            ->addMatchesExclude('/sub\.domain1\.tld/')
            ->setRequestURL('https://sub.domain.tld:80/path/to/page.html?foo=bar&test=123#abc');

        $config = $mappIntelligenceConfig->build();
        $this->assertEquals(false, $config['deactivateByInAndExclude']);
    }

    public function testRequestWithContainsAndMatchesExclude1()
    {
        $mappIntelligenceConfig = (new MappIntelligenceConfig())
            ->addContainsExclude('sub.domain.tld')
            ->addMatchesExclude('/sub\.domain1\.tld/')
            ->setRequestURL('https://sub.domain.tld:80/path/to/page.html?foo=bar&test=123#abc');

        $config = $mappIntelligenceConfig->build();
        $this->assertEquals(true, $config['deactivateByInAndExclude']);
    }

    public function testRequestWithContainsAndMatchesExclude2()
    {
        $mappIntelligenceConfig = (new MappIntelligenceConfig())
            ->addContainsExclude('sub.domain1.tld')
            ->addMatchesExclude('/sub\.domain\.tld/')
            ->setRequestURL('https://sub.domain.tld:80/path/to/page.html?foo=bar&test=123#abc');

        $config = $mappIntelligenceConfig->build();
        $this->assertEquals(true, $config['deactivateByInAndExclude']);
    }

    public function testRequestWithContainsAndMatchesExclude3()
    {
        $mappIntelligenceConfig = (new MappIntelligenceConfig())
            ->addContainsExclude('sub.domain1.tld')
            ->addMatchesExclude('/sub\.domain1\.tld/')
            ->setRequestURL('https://sub.domain.tld:80/path/to/page.html?foo=bar&test=123#abc');

        $config = $mappIntelligenceConfig->build();
        $this->assertEquals(false, $config['deactivateByInAndExclude']);
    }

    public function testRequestWithContainsIncludeAndExclude1()
    {
        $mappIntelligenceConfig = (new MappIntelligenceConfig())
            ->addContainsInclude('sub.domain.tld')
            ->addContainsExclude('.html')
            ->setRequestURL('https://sub.domain.tld:80/path/to/page.html?foo=bar&test=123#abc');

        $config = $mappIntelligenceConfig->build();
        $this->assertEquals(true, $config['deactivateByInAndExclude']);
    }

    public function testRequestWithContainsIncludeAndExclude2()
    {
        $mappIntelligenceConfig = (new MappIntelligenceConfig())
            ->addContainsInclude('sub.domain.tld')
            ->addContainsExclude('sub.domain1.tld')
            ->setRequestURL('https://sub.domain.tld:80/path/to/page.html?foo=bar&test=123#abc');

        $config = $mappIntelligenceConfig->build();
        $this->assertEquals(false, $config['deactivateByInAndExclude']);
    }

    public function testRequestWithContainsIncludeAndExclude3()
    {
        $mappIntelligenceConfig = (new MappIntelligenceConfig())
            ->addContainsInclude('sub.domain1.tld')
            ->addContainsExclude('sub.domain1.tld')
            ->setRequestURL('https://sub.domain.tld:80/path/to/page.html?foo=bar&test=123#abc');

        $config = $mappIntelligenceConfig->build();
        $this->assertEquals(true, $config['deactivateByInAndExclude']);
    }

    public function testRequestWithMatchesIncludeAndExclude1()
    {
        $mappIntelligenceConfig = (new MappIntelligenceConfig())
            ->addMatchesInclude('/sub\.domain\.tld/')
            ->addMatchesExclude('/\.html/')
            ->setRequestURL('https://sub.domain.tld:80/path/to/page.html?foo=bar&test=123#abc');

        $config = $mappIntelligenceConfig->build();
        $this->assertEquals(true, $config['deactivateByInAndExclude']);
    }

    public function testRequestWithMatchesIncludeAndExclude2()
    {
        $mappIntelligenceConfig = (new MappIntelligenceConfig())
            ->addMatchesInclude('/sub\.domain\.tld/')
            ->addMatchesExclude('/sub\.domain1\.tld/')
            ->setRequestURL('https://sub.domain.tld:80/path/to/page.html?foo=bar&test=123#abc');

        $config = $mappIntelligenceConfig->build();
        $this->assertEquals(false, $config['deactivateByInAndExclude']);
    }

    public function testRequestWithMatchesIncludeAndExclude3()
    {
        $mappIntelligenceConfig = (new MappIntelligenceConfig())
            ->addMatchesInclude('/sub\.domain1\.tld/')
            ->addMatchesExclude('/sub\.domain1\.tld/')
            ->setRequestURL('https://sub.domain.tld:80/path/to/page.html?foo=bar&test=123#abc');

        $config = $mappIntelligenceConfig->build();
        $this->assertEquals(true, $config['deactivateByInAndExclude']);
    }
}
