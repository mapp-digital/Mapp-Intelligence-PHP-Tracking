<?php

require_once __DIR__ . '/MappIntelligenceExtendsTestCase.php';

/**
 * Class MappIntelligenceCronjobTest
 */
class MappIntelligenceCronjobTest extends MappIntelligenceExtendsTestCase
{
    private $mic = null;

    public function testConfigFileNotFound()
    {
        $this->expectExceptionMessage(MappIntelligenceMessages::$REQUIRED_TRACK_ID);
        $this->mic = new MappIntelligenceCronjob(array(
            'c' => MAIN_DIRECTORY . 'tmp/config.ini'
        ));
    }

    public function testTrackIdIsRequired()
    {
        $this->expectExceptionMessage(MappIntelligenceMessages::$REQUIRED_TRACK_ID);
        $this->mic = new MappIntelligenceCronjob(array(
            'd' => 'q3.webtrekk.net'
        ));
    }

    public function testTrackIdIsRequired2()
    {
        $this->expectExceptionMessage(MappIntelligenceMessages::$REQUIRED_TRACK_ID);
        $this->mic = new MappIntelligenceCronjob(array(
            'trackDomain' => 'q3.webtrekk.net'
        ));
    }

    public function testTrackDomainIsRequired()
    {
        $this->expectExceptionMessage(MappIntelligenceMessages::$REQUIRED_TRACK_DOMAIN);
        $this->mic = new MappIntelligenceCronjob(array(
            'i' => '111111111111111'
        ));
    }

    public function testTrackDomainIsRequired2()
    {
        $this->expectExceptionMessage(MappIntelligenceMessages::$REQUIRED_TRACK_DOMAIN);
        $this->mic = new MappIntelligenceCronjob(array(
            'trackId' => '111111111111111'
        ));
    }

    public function testDefaultConfig()
    {
        $this->mic = new MappIntelligenceCronjob(array(
            'i' => '111111111111111',
            'd' => 'q3.webtrekk.net'
        ));

        $options = MappIntelligenceUnitUtil::getProperty($this->mic, 'cfg');
        $this->assertEquals('111111111111111', $options['trackId']);
        $this->assertEquals('q3.webtrekk.net', $options['trackDomain']);
        $this->assertEquals(sys_get_temp_dir() . '/MappIntelligenceRequests.log', $options['filename']);
        $this->assertEquals(true, $options['logger'] instanceof MappIntelligenceLogger);
        $this->assertEquals(1000, $options['maxBatchSize']);
        $this->assertEquals(100000, $options['maxQueueSize']);
    }

    public function testConfigFile()
    {
        $this->mic = new MappIntelligenceCronjob(array(
            'c' => MAIN_DIRECTORY . 'config.ini',
            'i' => '111111111111111',
            'd' => 'q3.webtrekk.net',
            'f' => sys_get_temp_dir() . '/MappIntelligenceRequests.log'
        ));

        $options = MappIntelligenceUnitUtil::getProperty($this->mic, 'cfg');
        $this->assertEquals('111111111111111', $options['trackId']);
        $this->assertEquals('q3.webtrekk.net', $options['trackDomain']);
        $this->assertEquals(sys_get_temp_dir() . '/MappIntelligenceRequests.log', $options['filename']);
        $this->assertEquals(true, $options['logger'] instanceof MappIntelligenceLogger);
        $this->assertEquals(1000, $options['maxBatchSize']);
        $this->assertEquals(100000, $options['maxQueueSize']);
    }

    public function testDefaultLogFileName()
    {
        $this->mic = new MappIntelligenceCronjob(array(
            'i' => '111111111111111',
            'd' => 'q3.webtrekk.net'
        ));

        $options = MappIntelligenceUnitUtil::getProperty($this->mic, 'cfg');
        $this->assertEquals(sys_get_temp_dir() . '/MappIntelligenceRequests.log', $options['filename']);
    }

    public function testOwnLogFileName()
    {
        $this->mic = new MappIntelligenceCronjob(array(
            'i' => '111111111111111',
            'd' => 'q3.webtrekk.net',
            'f' => MAIN_DIRECTORY . 'tmp/webtrekk.log',
            'debug' => true,
            'logLevel' => MappIntelligenceLogLevel::DEBUG
        ));

        $options = MappIntelligenceUnitUtil::getProperty($this->mic, 'cfg');
        $this->assertEquals(MAIN_DIRECTORY . 'tmp/webtrekk.log', $options['filename']);
    }

    public function testTrackingIsDeactivated()
    {
        $this->mic = new MappIntelligenceCronjob(array(
            'i' => '111111111111111',
            'd' => 'q3.webtrekk.net',
            'f' => MAIN_DIRECTORY . 'temp/webtrekk.log',
            'debug' => true,
            'logLevel' => MappIntelligenceLogLevel::DEBUG,
            'deactivate' => true
        ));

        $this->assertEquals(0, $this->mic->run());

        $fileContent = MappIntelligenceUnitUtil::getErrorLog();
        $this->assertContainsExtended('Mapp Intelligence tracking is deactivated', $fileContent[0]);
    }

    public function testRequestLogfileNotFound()
    {
        $this->mic = new MappIntelligenceCronjob(array(
            'i' => '111111111111111',
            'd' => 'q3.webtrekk.net',
            'f' => MAIN_DIRECTORY . 'temp/webtrekk.log',
            'debug' => true,
            'logLevel' => MappIntelligenceLogLevel::DEBUG
        ));

        $this->assertEquals(1, $this->mic->run());

        $fileContent = MappIntelligenceUnitUtil::getErrorLog();
        $this->assertContainsExtended(
            'Request log files "' . MAIN_DIRECTORY . 'temp/webtrekk.log" not found',
            $fileContent[0]
        );
    }

    public function testRenamingLogfileFailed()
    {
        MappIntelligenceUnitUtil::runkitFunctionRename('rename', 'origin_rename');
        MappIntelligenceUnitUtil::runkitFunctionAdd('rename', '', 'return false;');

        $handle = fopen(MAIN_DIRECTORY . 'tmp/foo_bar.log', 'a');

        $this->mic = new MappIntelligenceCronjob(array(
            'i' => '111111111111111',
            'd' => 'q3.webtrekk.net',
            'f' => MAIN_DIRECTORY . 'tmp/foo_bar.log',
            'debug' => true,
            'logLevel' => MappIntelligenceLogLevel::DEBUG
        ));

        $this->assertEquals(1, $this->mic->run());

        fclose($handle);
        unlink(MAIN_DIRECTORY . 'tmp/foo_bar.log');

        MappIntelligenceUnitUtil::runkitFunctionRemove('rename');
        MappIntelligenceUnitUtil::runkitFunctionRename('origin_rename', 'rename');
    }

    public function testEmptyLogfile()
    {
        $handle = fopen(MAIN_DIRECTORY . 'tmp/foo_bar.log', 'a');
        fclose($handle);

        $this->mic = new MappIntelligenceCronjob(array(
            'i' => '111111111111111',
            'd' => 'q3.webtrekk.net',
            'f' => MAIN_DIRECTORY . 'tmp/foo_bar.log',
            'debug' => true,
            'logLevel' => MappIntelligenceLogLevel::DEBUG
        ));

        $this->assertEquals(0, $this->mic->run());

        $fileContent = MappIntelligenceUnitUtil::getErrorLog();
        $this->assertContainsExtended('Sent batch requests, current queue size is 0 req.', $fileContent[0]);
        $this->assertContainsExtended('MappIntelligenceQueue is empty', $fileContent[1]);
    }

    public function testFlushLogfileFailed()
    {
        $handle = fopen(MAIN_DIRECTORY . 'tmp/foo_bar.log', 'a');
        fwrite($handle, "wt?p=300,0\n");
        fclose($handle);

        $this->mic = new MappIntelligenceCronjob(array(
            'i' => '111111111111111',
            'd' => 'q3.webtrekk.net',
            'f' => MAIN_DIRECTORY . 'tmp/foo_bar.log',
            'debug' => true,
            'logLevel' => MappIntelligenceLogLevel::DEBUG
        ));

        $this->assertEquals(1, $this->mic->run());

        $fileContent = MappIntelligenceUnitUtil::getErrorLog();
        $this->assertContainsExtended('Sent batch requests, current queue size is 1 req.', $fileContent[1]);
        $this->assertContainsExtended(
            'Send batch data to https://q3.webtrekk.net/111111111111111/batch (1 req.)',
            $fileContent[2]
        );
        $this->assertContainsExtended('Batch request responding the status code 404', $fileContent[3]);
        $this->assertContainsExtended('[0]:', $fileContent[4]);
        $this->assertContainsExtended('Batch request failed!', $fileContent[5]);
        $this->assertContainsExtended('Batch of 1 req. sent, current queue size is 1 req.', $fileContent[6]);

        $this->assertEquals("wt?p=300,0\n", file_get_contents(MAIN_DIRECTORY . 'tmp/foo_bar.log'));
    }

    public function testFlushLogfileSuccess()
    {
        $handle = fopen(MAIN_DIRECTORY . 'tmp/foo_bar.log', 'a');
        for ($i = 0; $i < 5; $i++) {
            fwrite($handle, "wt?p=300,0\n");
        }
        fclose($handle);

        $this->mic = new MappIntelligenceCronjob(array(
            'i' => '123451234512345',
            'd' => 'q3.webtrekk.net',
            'f' => MAIN_DIRECTORY . 'tmp/foo_bar.log',
            'debug' => true,
            'logLevel' => MappIntelligenceLogLevel::DEBUG
        ));

        $this->assertEquals(0, $this->mic->run());

        $fileContent = MappIntelligenceUnitUtil::getErrorLog();
        $this->assertContainsExtended('Sent batch requests, current queue size is 5 req.', $fileContent[5]);
        $this->assertContainsExtended(
            'Send batch data to https://q3.webtrekk.net/123451234512345/batch (5 req.)',
            $fileContent[6]
        );
        $this->assertContainsExtended('Batch request responding the status code 200', $fileContent[7]);
        $this->assertContainsExtended('Batch of 5 req. sent, current queue size is 0 req.', $fileContent[8]);
        $this->assertContainsExtended('MappIntelligenceQueue is empty', $fileContent[9]);
    }
}
