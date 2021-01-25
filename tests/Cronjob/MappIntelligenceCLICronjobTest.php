<?php

require_once __DIR__ . '/../MappIntelligenceExtendsTestCase.php';

/**
 * Class MappIntelligenceCLICronjobTest
 */
class MappIntelligenceCLICronjobTest extends MappIntelligenceExtendsTestCase
{
    const CONFIG_FILE = __DIR__ . '/../_cfg/config.ini';
    const FILE_PATH = __DIR__ . '/../../tmp/';
    const FILE_PREFIX = 'MappIntelligenceRequests';
    const TEMPORARY_FILE_EXTENSION = '.tmp';
    const LOG_FILE_EXTENSION = '.log';

    public function testHelp()
    {
        $args = array(
            'help' => true
        );

        try {
            new MappIntelligenceCLICronjob($args);
            $this->fail();
        } catch (Exception $e) {
            $fileContent = implode("\n", MappIntelligenceUnitUtil::getErrorLog());
            $this->assertTrue(strlen($fileContent) > 500);
        }
    }

    public function testVersion()
    {
        $args = array(
            'version' => true
        );

        try {
            new MappIntelligenceCLICronjob($args);
            $this->fail();
        } catch (Exception $e) {
            $fileContent = MappIntelligenceUnitUtil::getErrorLog();
            $this->assertContainsExtended('v' . MappIntelligenceVersion::get(), $fileContent[0]);
        }
    }

    public function testDebug()
    {
        $args = array(
            'c' => './config.ini',
            't' => MappIntelligenceConsumerType::FILE_ROTATION,
            'i' => '123451234512345',
            'd' => 'analytics01.wt-eu02.net',
            'debug' => true
        );

        try {
            $cronjob = new MappIntelligenceCLICronjob($args);
            $this->assertEquals(1, $cronjob->run());
            $this->assertTrue(true);
        } catch (Exception $e) {
            $this->fail($e->getMessage());
        }
    }

    public function testMappIntelligenceConfigRequiredTrackId()
    {
        $mappIntelligenceConfig = new MappIntelligenceConfig();

        try {
            new MappIntelligenceCLICronjob($mappIntelligenceConfig);
            $this->fail();
        } catch (Exception $e) {
            $this->assertEquals('Argument "-i" or alternative "--trackId" are required', $e->getMessage());
        }
    }

    public function testMappIntelligenceConfigRequiredTrackDomain()
    {
        $mappIntelligenceConfig = new MappIntelligenceConfig();
        $mappIntelligenceConfig->setTrackId('123451234512345');

        try {
            new MappIntelligenceCLICronjob($mappIntelligenceConfig);
            $this->fail();
        } catch (Exception $e) {
            $this->assertEquals('Argument "-d" or alternative "--trackDomain" are required', $e->getMessage());
        }
    }

    public function testCommandLineArgumentsRequiredTrackId()
    {
        try {
            new MappIntelligenceCLICronjob(array());
            $this->fail();
        } catch (Exception $e) {
            $this->assertEquals('Argument "-i" or alternative "--trackId" are required', $e->getMessage());
        }
    }

    public function testCommandLineArgumentsRequiredTrackDomain1()
    {
        $args = array('i' => '123451234512345');

        try {
            new MappIntelligenceCLICronjob($args);
            $this->fail();
        } catch (Exception $e) {
            $this->assertEquals('Argument "-d" or alternative "--trackDomain" are required', $e->getMessage());
        }
    }

    public function testCommandLineArgumentsRequiredTrackDomain2()
    {
        $args = array('trackId' => '123451234512345');

        try {
            new MappIntelligenceCLICronjob($args);
            $this->fail();
        } catch (Exception $e) {
            $this->assertEquals('Argument "-d" or alternative "--trackDomain" are required', $e->getMessage());
        }
    }

    public function testMappIntelligenceWithTrackIdAndTrackDomain()
    {
        $mappIntelligenceConfig = new MappIntelligenceConfig();
        $mappIntelligenceConfig->setTrackId('123451234512345');
        $mappIntelligenceConfig->setTrackDomain('q3.webtrekk.net');

        try {
            new MappIntelligenceCLICronjob($mappIntelligenceConfig);
            $this->assertTrue(true);
        } catch (Exception $e) {
            $this->fail();
        }
    }

    public function testCommandLineArgumentsWithTrackIdAndTrackDomain1()
    {
        $args = array('i' => '123451234512345', 'd' => 'q3.webtrekk.net');

        try {
            new MappIntelligenceCLICronjob($args);
            $this->assertTrue(true);
        } catch (Exception $e) {
            $this->fail();
        }
    }

    public function testCommandLineArgumentsWithTrackIdAndTrackDomain2()
    {
        $args = array('trackId' => '123451234512345', 'trackDomain' => 'q3.webtrekk.net');

        try {
            new MappIntelligenceCLICronjob($args);
            $this->assertTrue(true);
        } catch (Exception $e) {
            $this->fail();
        }
    }

    public function testMappIntelligenceConfigAll()
    {
        $mappIntelligenceConfig = new MappIntelligenceConfig();
        $mappIntelligenceConfig->setTrackId('123451234512345');
        $mappIntelligenceConfig->setTrackDomain('q3.webtrekk.net');
        $mappIntelligenceConfig->setFilePath('/tmp/');
        $mappIntelligenceConfig->setFilePrefix('MappIntelligenceData');

        try {
            new MappIntelligenceCLICronjob($mappIntelligenceConfig);
            $this->assertTrue(true);
        } catch (Exception $e) {
            $this->fail();
        }
    }

    public function testCommandLineArgumentsConfigAll1()
    {
        $args = array(
            'i' => '123451234512345',
            'd' => 'q3.webtrekk.net',
            't' => MappIntelligenceConsumerType::FILE_ROTATION,
            'f' => '/tmp/',
            'p' => 'MappIntelligenceData'
        );

        try {
            new MappIntelligenceCLICronjob($args);
            $this->assertTrue(true);
        } catch (Exception $e) {
            $this->fail();
        }
    }

    public function testCommandLineArgumentsConfigAll2()
    {
        $args = array(
            'trackId' => '123451234512345',
            'trackDomain' => 'q3.webtrekk.net',
            'consumerType' => MappIntelligenceConsumerType::FILE_ROTATION,
            'filePath' => '/tmp/',
            'filePrefix' => 'MappIntelligenceData'
        );

        try {
            new MappIntelligenceCLICronjob($args);
            $this->assertTrue(true);
        } catch (Exception $e) {
            $this->fail();
        }
    }

    public function testMappIntelligenceInvalidConfigFile()
    {
        $mappIntelligenceConfig = new MappIntelligenceConfig('./foo.bar');

        try {
            new MappIntelligenceCLICronjob($mappIntelligenceConfig);
            $this->fail();
        } catch (Exception $e) {
            $this->assertEquals('Argument "-i" or alternative "--trackId" are required', $e->getMessage());
        }
    }

    public function testCommandLineArgumentsInvalidConfigFile1()
    {
        $args = array('c' => 'foo.bar');

        try {
            new MappIntelligenceCLICronjob($args);
            $this->fail();
        } catch (Exception $e) {
            $this->assertEquals('Argument "-i" or alternative "--trackId" are required', $e->getMessage());
        }
    }

    public function testCommandLineArgumentsInvalidConfigFile2()
    {
        $args = array('config' => 'foo.bar');

        try {
            new MappIntelligenceCLICronjob($args);
            $this->fail();
        } catch (Exception $e) {
            $this->assertEquals('Argument "-i" or alternative "--trackId" are required', $e->getMessage());
        }
    }

    public function testMappIntelligenceConfigFile()
    {
        $mappIntelligenceConfig = new MappIntelligenceConfig(self::CONFIG_FILE);

        try {
            new MappIntelligenceCLICronjob($mappIntelligenceConfig);
            $this->assertTrue(true);
        } catch (Exception $e) {
            $this->fail();
        }
    }

    public function testCommandLineArgumentsConfigFile1()
    {
        $args = array('c' => self::CONFIG_FILE);

        try {
            new MappIntelligenceCLICronjob($args);
            $this->assertTrue(true);
        } catch (Exception $e) {
            $this->fail();
        }
    }

    public function testCommandLineArgumentsConfigFile2()
    {
        $args = array('config' => self::CONFIG_FILE);

        try {
            new MappIntelligenceCLICronjob($args);
            $this->assertTrue(true);
        } catch (Exception $e) {
            $this->fail();
        }
    }

    public function testCommandLineArgumentsUnsupportedOptionException()
    {
        $args = array('foo' => '123451234512345', 'bar' => 'q3.webtrekk.net');

        try {
            new MappIntelligenceCLICronjob($args);
            $this->fail();
        } catch (Exception $e) {
            $this->assertEquals('Unsupported config option (foo=123451234512345)', $e->getMessage());
        }
    }

    public function testRunDeactivated()
    {
        $mappIntelligenceConfig = new MappIntelligenceConfig(self::CONFIG_FILE);
        $mappIntelligenceConfig->setDeactivate(true);
        $mappIntelligenceConfig->setDebug(true);

        try {
            $cron = new MappIntelligenceCLICronjob($mappIntelligenceConfig);
            $this->assertEquals(0, $cron->run());

            $fileContent = MappIntelligenceUnitUtil::getErrorLog();
            $this->assertContainsExtended('Mapp Intelligence tracking is deactivated', $fileContent[0]);
        } catch (Exception $e) {
            $this->fail();
        }
    }

    public function testRenameTemporaryFiles()
    {
        $time = 1400000000000;
        for ($i = 0; $i < 10; $i++) {
            $time += $i * 10;
            MappIntelligenceUnitUtil::createFile(
                self::FILE_PATH . self::FILE_PREFIX . '-' . $time . self::TEMPORARY_FILE_EXTENSION
            );
        }

        $mappIntelligenceConfig = new MappIntelligenceConfig(self::CONFIG_FILE);
        $mappIntelligenceConfig->setConsumerType(MappIntelligenceConsumerType::FILE_ROTATION);
        $mappIntelligenceConfig->setFilePath(self::FILE_PATH);
        $mappIntelligenceConfig->setFilePrefix(self::FILE_PREFIX);

        try {
            $cron = new MappIntelligenceCLICronjob($mappIntelligenceConfig);
            $this->assertEquals(0, $cron->run());

            $logFiles = MappIntelligenceUnitUtil::getFiles(
                self::FILE_PATH,
                self::FILE_PREFIX,
                self::LOG_FILE_EXTENSION
            );
            $this->assertEquals(0, count($logFiles));

            $temporaryFiles = MappIntelligenceUnitUtil::getFiles(
                self::FILE_PATH,
                self::FILE_PREFIX,
                self::TEMPORARY_FILE_EXTENSION
            );
            $this->assertEquals(0, count($temporaryFiles));
        } catch (Exception $e) {
            $this->fail();
        }
    }

    public function testEmptyDirectory()
    {
        $mappIntelligenceConfig = new MappIntelligenceConfig(self::CONFIG_FILE);
        $mappIntelligenceConfig->setConsumerType(MappIntelligenceConsumerType::FILE_ROTATION);
        $mappIntelligenceConfig->setFilePath(self::FILE_PATH);
        $mappIntelligenceConfig->setFilePrefix(self::FILE_PREFIX);

        try {
            $cron = new MappIntelligenceCLICronjob($mappIntelligenceConfig);
            $this->assertEquals(1, $cron->run());
        } catch (Exception $e) {
            $this->fail();
        }
    }

    public function testRequestLogFileNotExpired()
    {
        $mappIntelligenceConfig = new MappIntelligenceConfig(self::CONFIG_FILE);
        $mappIntelligenceConfig->setTrackId('111111111111111');
        $mappIntelligenceConfig->setConsumerType(MappIntelligenceConsumerType::FILE_ROTATION);
        $mappIntelligenceConfig->setFilePath(self::FILE_PATH);
        $mappIntelligenceConfig->setFilePrefix(self::FILE_PREFIX);

        $mapp = new MappIntelligenceQueue($mappIntelligenceConfig->build());
        for ($i = 0; $i < 100; $i++) {
            $mapp->add('wt?p=400,' . $i);
            $mapp->flush();
        }

        try {
            $cron = new MappIntelligenceCLICronjob($mappIntelligenceConfig);
            $this->assertEquals(1, $cron->run());

            $logFiles = MappIntelligenceUnitUtil::getFiles(
                self::FILE_PATH,
                self::FILE_PREFIX,
                self::LOG_FILE_EXTENSION
            );
            $this->assertEquals(0, count($logFiles));

            $temporaryFiles = MappIntelligenceUnitUtil::getFiles(
                self::FILE_PATH,
                self::FILE_PREFIX,
                self::TEMPORARY_FILE_EXTENSION
            );
            $this->assertEquals(1, count($temporaryFiles));

            MappIntelligenceUnitUtil::deleteFiles(self::FILE_PATH, self::FILE_PREFIX, self::TEMPORARY_FILE_EXTENSION);
        } catch (Exception $e) {
            $this->fail();
        }
    }

    public function testSendBatchFail()
    {
        $mappIntelligenceConfig = new MappIntelligenceConfig(self::CONFIG_FILE);
        $mappIntelligenceConfig->setTrackId('111111111111111');
        $mappIntelligenceConfig->setConsumerType(MappIntelligenceConsumerType::FILE_ROTATION);
        $mappIntelligenceConfig->setFilePath(self::FILE_PATH);
        $mappIntelligenceConfig->setFilePrefix(self::FILE_PREFIX);
        $mappIntelligenceConfig->setMaxFileLines(25);

        $mapp = new MappIntelligenceQueue($mappIntelligenceConfig->build());
        for ($i = 0; $i < 101; $i++) {
            $mapp->add('wt?p=400,' . $i);
            $mapp->flush();
        }

        try {
            $cron = new MappIntelligenceCLICronjob($mappIntelligenceConfig);
            $this->assertEquals(1, $cron->run());

            $logFiles = MappIntelligenceUnitUtil::getFiles(
                self::FILE_PATH,
                self::FILE_PREFIX,
                self::LOG_FILE_EXTENSION
            );
            $this->assertEquals(4, count($logFiles));

            $temporaryFiles = MappIntelligenceUnitUtil::getFiles(
                self::FILE_PATH,
                self::FILE_PREFIX,
                self::TEMPORARY_FILE_EXTENSION
            );
            $this->assertEquals(1, count($temporaryFiles));

            MappIntelligenceUnitUtil::deleteFiles(self::FILE_PATH, self::FILE_PREFIX, self::LOG_FILE_EXTENSION);
            MappIntelligenceUnitUtil::deleteFiles(self::FILE_PATH, self::FILE_PREFIX, self::TEMPORARY_FILE_EXTENSION);
        } catch (Exception $e) {
            $this->fail();
        }
    }

    public function testSendBatchSuccess()
    {
        $mappIntelligenceConfig = new MappIntelligenceConfig(self::CONFIG_FILE);
        $mappIntelligenceConfig->setTrackId('123451234512345');
        $mappIntelligenceConfig->setConsumerType(MappIntelligenceConsumerType::FILE_ROTATION);
        $mappIntelligenceConfig->setFilePath(self::FILE_PATH);
        $mappIntelligenceConfig->setFilePrefix(self::FILE_PREFIX);
        $mappIntelligenceConfig->setMaxFileLines(25);

        $mapp = new MappIntelligenceQueue($mappIntelligenceConfig->build());
        for ($i = 0; $i < 101; $i++) {
            $mapp->add('wt?p=400,' . $i);
            $mapp->flush();
        }

        try {
            $cron = new MappIntelligenceCLICronjob($mappIntelligenceConfig);
            $this->assertEquals(0, $cron->run());

            $logFiles = MappIntelligenceUnitUtil::getFiles(
                self::FILE_PATH,
                self::FILE_PREFIX,
                self::LOG_FILE_EXTENSION
            );
            $this->assertEquals(0, count($logFiles));

            $temporaryFiles = MappIntelligenceUnitUtil::getFiles(
                self::FILE_PATH,
                self::FILE_PREFIX,
                self::TEMPORARY_FILE_EXTENSION
            );
            $this->assertEquals(1, count($temporaryFiles));

            MappIntelligenceUnitUtil::deleteFiles(self::FILE_PATH, self::FILE_PREFIX, self::TEMPORARY_FILE_EXTENSION);
        } catch (Exception $e) {
            $this->fail();
        }
    }
}
