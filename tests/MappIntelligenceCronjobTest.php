<?php

/**
 * Class MappIntelligenceCronjobTest
 */
class MappIntelligenceCronjobTest extends PHPUnit\Framework\TestCase
{
    public function testConfigFileNotFound()
    {
        $this->expectExceptionMessage('config file "./tmp/config.ini" not found');
        new MappIntelligenceCronjob(array(
            'c' => './tmp/config.ini'
        ));
    }

    public function testTrackIdIsRequired()
    {
        $this->expectExceptionMessage('argument "-i" or alternative "--trackId" are required');
        new MappIntelligenceCronjob(array(
            'd' => 'q3.webtrekk.net'
        ));
    }

    public function testTrackIdIsRequired2()
    {
        $this->expectExceptionMessage('argument "-i" or alternative "--trackId" are required');
        new MappIntelligenceCronjob(array(
            'trackDomain' => 'q3.webtrekk.net'
        ));
    }

    public function testTrackDomainIsRequired()
    {
        $this->expectExceptionMessage('argument "-d" or alternative "--trackDomain" are required');
        new MappIntelligenceCronjob(array(
            'i' => '111111111111111'
        ));
    }

    public function testTrackDomainIsRequired2()
    {
        $this->expectExceptionMessage('argument "-d" or alternative "--trackDomain" are required');
        new MappIntelligenceCronjob(array(
            'trackId' => '111111111111111'
        ));
    }

    public function testDefaultConfig()
    {
        $cron = new MappIntelligenceCronjob(array(
            'i' => '111111111111111',
            'd' => 'q3.webtrekk.net'
        ));

        $options = MappIntelligenceUnitUtil::getProperty($cron, 'options');
        $this->assertEquals('111111111111111', $options['trackId']);
        $this->assertEquals('q3.webtrekk.net', $options['trackDomain']);
        $this->assertEquals(sys_get_temp_dir() . '/MappIntelligenceRequests.log', $options['filename']);
        $this->assertEquals(false, $options['debug']);
        $this->assertEquals(1000, $options['maxBatchSize']);
        $this->assertEquals(100000, $options['maxQueueSize']);
    }

    public function testConfigFile()
    {
        $cron = new MappIntelligenceCronjob(array(
            'c' => './config.ini',
            'i' => '111111111111111',
            'd' => 'q3.webtrekk.net',
            'f' => sys_get_temp_dir() . '/MappIntelligenceRequests.log'
        ));

        $options = MappIntelligenceUnitUtil::getProperty($cron, 'options');
        $this->assertEquals('111111111111111', $options['trackId']);
        $this->assertEquals('q3.webtrekk.net', $options['trackDomain']);
        $this->assertEquals(sys_get_temp_dir() . '/MappIntelligenceRequests.log', $options['filename']);
        $this->assertEquals(true, $options['debug']);
        $this->assertEquals(1000, $options['maxBatchSize']);
        $this->assertEquals(100000, $options['maxQueueSize']);
    }

    public function testDefaultLogFileName()
    {
        $cron = new MappIntelligenceCronjob(array(
            'i' => '111111111111111',
            'd' => 'q3.webtrekk.net'
        ));

        $options = MappIntelligenceUnitUtil::getProperty($cron, 'options');
        $this->assertEquals(sys_get_temp_dir() . '/MappIntelligenceRequests.log', $options['filename']);
    }

    public function testOwnLogFileName()
    {
        $cron = new MappIntelligenceCronjob(array(
            'i' => '111111111111111',
            'd' => 'q3.webtrekk.net',
            'f' => './tmp/webtrekk.log',
            'debug' => true
        ));

        $options = MappIntelligenceUnitUtil::getProperty($cron, 'options');
        $this->assertEquals('./tmp/webtrekk.log', $options['filename']);
    }

    public function testTrackingIsDeactivated()
    {
        $cron = new MappIntelligenceCronjob(array(
            'i' => '111111111111111',
            'd' => 'q3.webtrekk.net',
            'f' => './temp/webtrekk.log',
            'deactivate' => true
        ));

        $this->assertEquals('Mapp Intelligence tracking is deactivated', $cron->run());
    }

    public function testRequestLogfileNotFound()
    {
        $cron = new MappIntelligenceCronjob(array(
            'i' => '111111111111111',
            'd' => 'q3.webtrekk.net',
            'f' => './temp/webtrekk.log',
            'debug' => true
        ));

        $this->assertEquals('request logfile "./temp/webtrekk.log" not found', $cron->run());
    }

    public function testRenamingLogfileFailed()
    {
        runkit_function_rename('rename', 'origin_rename');
        runkit_function_add('rename', '', 'return false;');

        $handle = fopen('./tmp/foo_bar.log', 'a');

        $cron = new MappIntelligenceCronjob(array(
            'i' => '111111111111111',
            'd' => 'q3.webtrekk.net',
            'f' => './tmp/foo_bar.log',
            'debug' => true
        ));

        $this->assertRegExp(
            '/renaming from "\.\/tmp\/foo_bar\.log" to "\.\/tmp\/MappIntelligenceRequests\-\d+\.log" failed/',
            $cron->run()
        );

        fclose($handle);
        unlink('./tmp/foo_bar.log');

        runkit_function_remove('rename');
        runkit_function_rename('origin_rename', 'rename');
    }

    public function testEmptyLogfile()
    {
        $handle = fopen('./tmp/foo_bar.log', 'a');
        fclose($handle);

        $cron = new MappIntelligenceCronjob(array(
            'i' => '111111111111111',
            'd' => 'q3.webtrekk.net',
            'f' => './tmp/foo_bar.log',
            'debug' => true
        ));

        $this->assertEquals(0, $cron->run());

        $fileContent = MappIntelligenceUnitUtil::getErrorLog();
        $this->assertContains('Sent batch requests, current queue size is 0 req.', $fileContent[0]);
        $this->assertContains('MappIntelligenceQueue is empty', $fileContent[1]);
    }

    public function testFlushLogfileFailed()
    {
        $handle = fopen('./tmp/foo_bar.log', 'a');
        fwrite($handle, "wt?p=300,0\n");
        fclose($handle);

        $cron = new MappIntelligenceCronjob(array(
            'i' => '111111111111111',
            'd' => 'q3.webtrekk.net',
            'f' => './tmp/foo_bar.log',
            'debug' => true
        ));

        $this->assertEquals(1, $cron->run());

        $fileContent = MappIntelligenceUnitUtil::getErrorLog();
        $this->assertContains('Sent batch requests, current queue size is 1 req.', $fileContent[1]);
        $this->assertContains(
            'Send batch data via cURL call to https://q3.webtrekk.net/111111111111111/batch (1 req.)',
            $fileContent[2]
        );
        $this->assertContains('Batch request responding the status code 404', $fileContent[3]);
        $this->assertContains('[0]:', $fileContent[4]);
        $this->assertContains('Batch request failed!', $fileContent[5]);
        $this->assertContains('Batch of 1 req. sent, current queue size is 1 req.', $fileContent[6]);

        $this->assertEquals("wt?p=300,0\n", file_get_contents('./tmp/foo_bar.log'));
    }

    public function testFlushLogfileSuccess()
    {
        $handle = fopen('./tmp/foo_bar.log', 'a');
        for ($i = 0; $i < 5; $i++) {
            fwrite($handle, "wt?p=300,0\n");
        }
        fclose($handle);

        $cron = new MappIntelligenceCronjob(array(
            'i' => '123451234512345',
            'd' => 'q3.webtrekk.net',
            'f' => './tmp/foo_bar.log',
            'debug' => true
        ));

        $this->assertEquals(0, $cron->run());

        $fileContent = MappIntelligenceUnitUtil::getErrorLog();
        $this->assertContains('Sent batch requests, current queue size is 5 req.', $fileContent[5]);
        $this->assertContains(
            'Send batch data via cURL call to https://q3.webtrekk.net/123451234512345/batch (5 req.)',
            $fileContent[6]
        );
        $this->assertContains('Batch request responding the status code 200', $fileContent[7]);
        $this->assertContains('Batch of 5 req. sent, current queue size is 0 req.', $fileContent[8]);
        $this->assertContains('MappIntelligenceQueue is empty', $fileContent[9]);
    }
}
