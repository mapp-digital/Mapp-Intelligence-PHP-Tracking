<?php

/**
 * Class MappIntelligenceConfigTest
 */
class MappIntelligenceConfigTest extends PHPUnit\Framework\TestCase
{
    public function testDefaultConfig()
    {
        $mappIntelligence = MappIntelligence::getInstance();

        $config = $this->readAttribute($mappIntelligence, 'config_');
        $this->assertEquals('', $config['trackId']);
        $this->assertEquals('', $config['trackDomain']);
        $this->assertEquals(1, count($config['domain']));
        $this->assertEquals(false, $config['debug']);
        $this->assertEquals('curl', $config['consumer']);
        $this->assertStringEndsWith('/MappIntelligenceRequests.log', $config['filename']);
        $this->assertEquals('a', $config['fileMode']);
        $this->assertEquals(1, $config['maxAttempt']);
        $this->assertEquals(100, $config['attemptTimeout']);
        $this->assertEquals(50, $config['maxBatchSize']);
        $this->assertEquals(1000, $config['maxQueueSize']);
        $this->assertEquals(true, $config['forceSSL']);
        $this->assertEquals(0, count($config['useParamsForDefaultPageName']));
    }

    public function testConfigFile()
    {
        $mappIntelligence = MappIntelligence::getInstance(array(
            'config' => './config.ini'
        ));

        $config = $this->readAttribute($mappIntelligence, 'config_');
        $this->assertEquals('', $config['trackId']);
        $this->assertEquals('', $config['trackDomain']);
        $this->assertEquals(1, count($config['domain']));
        $this->assertEquals(false, $config['debug']);
        $this->assertEquals('curl', $config['consumer']);
        $this->assertStringEndsWith('/MappIntelligenceRequests.log', $config['filename']);
        $this->assertEquals(1, $config['maxAttempt']);
        $this->assertEquals(100, $config['attemptTimeout']);
        $this->assertEquals(50, $config['maxBatchSize']);
        $this->assertEquals(1000, $config['maxQueueSize']);
        $this->assertEquals(true, $config['forceSSL']);
        $this->assertEquals(0, count($config['useParamsForDefaultPageName']));
    }

    public function testOverwriteConfigFile()
    {
        $mappIntelligence = MappIntelligence::getInstance(array(
            'config' => './config.ini',
            'trackId' => '111111111111111',
            'trackDomain' => 'analytics01.wt-eu02.net',
            'maxAttempt' => 3,
            'attemptTimeout' => 200,
            'maxBatchSize' => 1000,
            'maxQueueSize' => 100000
        ));

        $config = $this->readAttribute($mappIntelligence, 'config_');
        $this->assertEquals('111111111111111', $config['trackId']);
        $this->assertEquals('analytics01.wt-eu02.net', $config['trackDomain']);
        $this->assertEquals(1, count($config['domain']));
        $this->assertEquals(false, $config['debug']);
        $this->assertEquals('curl', $config['consumer']);
        $this->assertStringEndsWith('/MappIntelligenceRequests.log', $config['filename']);
        $this->assertEquals(3, $config['maxAttempt']);
        $this->assertEquals(200, $config['attemptTimeout']);
        $this->assertEquals(1000, $config['maxBatchSize']);
        $this->assertEquals(100000, $config['maxQueueSize']);
        $this->assertEquals(true, $config['forceSSL']);
        $this->assertEquals(0, count($config['useParamsForDefaultPageName']));
    }

    public function testConfigWithTrackIdAndTrackDomain()
    {
        $mappIntelligence = MappIntelligence::getInstance(array(
            'trackId' => '111111111111111',
            'trackDomain' => 'analytics01.wt-eu02.net'
        ));

        $config = $this->readAttribute($mappIntelligence, 'config_');
        $this->assertEquals('111111111111111', $config['trackId']);
        $this->assertEquals('analytics01.wt-eu02.net', $config['trackDomain']);
    }

    public function testConfigWithDomain()
    {
        $mappIntelligence = MappIntelligence::getInstance(array(
            'domain' => array(
                'foo.bar.com', 'www.mappIntelligence.com', 'sub.domain.tld'
            )
        ));

        $config = $this->readAttribute($mappIntelligence, 'config_');
        $this->assertEquals('foo.bar.com', $config['domain'][0]);
        $this->assertEquals('www.mappIntelligence.com', $config['domain'][1]);
        $this->assertEquals('sub.domain.tld', $config['domain'][2]);
    }

    public function testConfigWithFile()
    {
        $mappIntelligence = MappIntelligence::getInstance(array(
            'consumer' => 'file',
            'filename' => '/dev/null'
        ));

        $config = $this->readAttribute($mappIntelligence, 'config_');
        $this->assertEquals('/dev/null', $config['filename']);
        $this->assertEquals('file', $config['consumer']);
        $this->assertEquals(1, $config['maxBatchSize']);
    }

    public function testInvalidMaxAttempt()
    {
        $mappIntelligence = MappIntelligence::getInstance(array(
            'maxAttempt' => 12
        ));

        $config = $this->readAttribute($mappIntelligence, 'config_');
        $this->assertEquals(1, $config['maxAttempt']);
    }

    public function testInvalidAttemptTimeout()
    {
        $mappIntelligence = MappIntelligence::getInstance(array(
            'attemptTimeout' => 750
        ));

        $config = $this->readAttribute($mappIntelligence, 'config_');
        $this->assertEquals(200, $config['attemptTimeout']);
    }

    public function testInvalidFileMode()
    {
        $mappIntelligence = MappIntelligence::getInstance(array(
            'consumer' => 'file',
            'fileMode' => 'x'
        ));

        $config = $this->readAttribute($mappIntelligence, 'config_');
        $this->assertEquals('a', $config['fileMode']);
    }

    public function testValidFileMode()
    {
        $mappIntelligence = MappIntelligence::getInstance(array(
            'consumer' => 'file',
            'fileMode' => 'c'
        ));

        $config = $this->readAttribute($mappIntelligence, 'config_');
        $this->assertEquals('c', $config['fileMode']);
    }
}
