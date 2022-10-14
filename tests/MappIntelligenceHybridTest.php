<?php

require_once __DIR__ . '/MappIntelligenceExtendsTestCase.php';

/**
 * Class MappIntelligenceCronjobTest
 */
class MappIntelligenceHybridTest extends MappIntelligenceExtendsTestCase
{
    public function testDefaultConfig()
    {
        $hybrid = new MappIntelligenceHybrid(array());

        $options = MappIntelligenceUnitUtil::getProperty($hybrid, 'cfg');
        $this->assertEquals(MappIntelligenceConsumerType::FILE, $options['consumerType']);
        $this->assertEquals(sys_get_temp_dir() . '/MappIntelligenceRequests.log', $options['filename']);
        $this->assertEquals(14, $options['statistics']);
    }

    public function testConfigFile()
    {
        $hybrid = new MappIntelligenceHybrid(array(
            'config' => MAIN_DIRECTORY . 'config.ini'
        ));

        $options = MappIntelligenceUnitUtil::getProperty($hybrid, 'cfg');
        $this->assertEquals(MappIntelligenceConsumerType::FILE, $options['consumerType']);
        $this->assertEquals(sys_get_temp_dir() . '/MappIntelligenceRequests.log', $options['filename']);
        $this->assertEquals(14, $options['statistics']);
    }

    public function testConsumerTypeFileRotation()
    {
        $hybrid = new MappIntelligenceHybrid(array(
            'consumerType' => MappIntelligenceConsumerType::FILE_ROTATION
        ));

        $options = MappIntelligenceUnitUtil::getProperty($hybrid, 'cfg');
        $this->assertEquals(MappIntelligenceConsumerType::FILE_ROTATION, $options['consumerType']);
        $this->assertEquals(134, $options['statistics']);
    }

    public function testConsumerTypeCustom()
    {
        $hybrid = new MappIntelligenceHybrid(array(
            'consumerType' => MappIntelligenceConsumerType::CUSTOM
        ));

        $options = MappIntelligenceUnitUtil::getProperty($hybrid, 'cfg');
        $this->assertEquals(MappIntelligenceConsumerType::CUSTOM, $options['consumerType']);
        $this->assertEquals(262, $options['statistics']);
    }

    public function testConsumerTypeUnsupported()
    {
        $hybrid = new MappIntelligenceHybrid(array(
            'consumerType' => MappIntelligenceConsumerType::FORK_CURL
        ));

        $options = MappIntelligenceUnitUtil::getProperty($hybrid, 'cfg');
        $this->assertEquals(MappIntelligenceConsumerType::FILE, $options['consumerType']);
        $this->assertEquals(22, $options['statistics']);
    }

    public function testOwnLogFileName()
    {
        $hybrid = new MappIntelligenceHybrid(array(
            'filename' => './tmp/webtrekk.log'
        ));

        $options = MappIntelligenceUnitUtil::getProperty($hybrid, 'cfg');
        $this->assertEquals('./tmp/webtrekk.log', $options['filename']);
    }

    /**
     * @runInSeparateProcess
     */
    public function testTrackingIsDeactivated()
    {
        $_SERVER['QUERY_STRING'] = 'p=600,manieren_per_mausklick,1,1920x1200,24,1,1579682664733,https%3A%2F%2';
        $_SERVER['QUERY_STRING'] .= 'Fwww.google.com%2F,1921x1036,0&eid=2157840760747614879&fns=1&one=0&cg1=ho';
        $_SERVER['QUERY_STRING'] .= 'me&cg15=Manieren%20per%20mausklick&uc8=0&uc9=0&cs7=non%20paywall%20user&t';
        $_SERVER['QUERY_STRING'] .= 'z=1&pu=https%3A%2F%2Fwww.knigge.de%2F&la=de&eor=1';

        $hybrid = new MappIntelligenceHybrid(array(
            'filename' => './tmp/webtrekk.log',
            'deactivate' => true
        ));

        $this->expectOutputString(base64_decode('R0lGODlhAQABAIAAAP///wAAACH5BAEAAAAALAAAAAABAAEAAAICRAEAOw=='));
        $hybrid->run();

        $this->assertEquals(false, file_exists('./tmp/webtrekk.log'));

        unset($_SERVER['QUERY_STRING']);
    }

    /**
     * @runInSeparateProcess
     */
    public function testQueryStringNotDefined1()
    {
        $hybrid = new MappIntelligenceHybrid(array(
            'filename' => './tmp/webtrekk.log'
        ));

        $this->expectOutputString(base64_decode('R0lGODlhAQABAIAAAP///wAAACH5BAEAAAAALAAAAAABAAEAAAICRAEAOw=='));
        $hybrid->run();

        $this->assertEquals(false, file_exists('./tmp/webtrekk.log'));
    }

    /**
     * @runInSeparateProcess
     */
    public function testQueryStringNotDefined2()
    {
        $hybrid = new MappIntelligenceHybrid(array(
            'filename' => './tmp/webtrekk.log',
            'requestURL' => 'https://sub.domain.tld/pix'
        ));

        $this->expectOutputString(base64_decode('R0lGODlhAQABAIAAAP///wAAACH5BAEAAAAALAAAAAABAAEAAAICRAEAOw=='));
        $hybrid->run();

        $this->assertEquals(false, file_exists('./tmp/webtrekk.log'));
    }

    /**
     * @runInSeparateProcess
     */
    public function testQueryStringNotDefined3()
    {
        $hybrid = new MappIntelligenceHybrid(array(
            'filename' => './tmp/webtrekk.log'
        ));

        $hybrid->setRequestURL('https://sub.domain.tld/pix');

        $this->expectOutputString(base64_decode('R0lGODlhAQABAIAAAP///wAAACH5BAEAAAAALAAAAAABAAEAAAICRAEAOw=='));
        $hybrid->run();

        $this->assertEquals(false, file_exists('./tmp/webtrekk.log'));
    }

    /**
     * @runInSeparateProcess
     */
    public function testEmptyQueryString1()
    {
        $_SERVER['QUERY_STRING'] = '';

        $hybrid = new MappIntelligenceHybrid(array(
            'filename' => './tmp/webtrekk.log'
        ));

        $this->expectOutputString(base64_decode('R0lGODlhAQABAIAAAP///wAAACH5BAEAAAAALAAAAAABAAEAAAICRAEAOw=='));
        $hybrid->run();

        $this->assertEquals(false, file_exists('./tmp/webtrekk.log'));

        unset($_SERVER['QUERY_STRING']);
    }

    /**
     * @runInSeparateProcess
     */
    public function testEmptyQueryString2()
    {
        $_SERVER['QUERY_STRING'] = '';

        $hybrid = new MappIntelligenceHybrid(array(
            'filename' => './tmp/webtrekk.log',
            'requestURL' => 'https://sub.domain.tld/pix?'
        ));

        $this->expectOutputString(base64_decode('R0lGODlhAQABAIAAAP///wAAACH5BAEAAAAALAAAAAABAAEAAAICRAEAOw=='));
        $hybrid->run();

        $this->assertEquals(false, file_exists('./tmp/webtrekk.log'));

        unset($_SERVER['QUERY_STRING']);
    }

    /**
     * @runInSeparateProcess
     */
    public function testEmptyQueryString3()
    {
        $_SERVER['QUERY_STRING'] = '';

        $hybrid = new MappIntelligenceHybrid(array(
            'filename' => './tmp/webtrekk.log'
        ));

        $hybrid->setRequestURL('https://sub.domain.tld/pix?');

        $this->expectOutputString(base64_decode('R0lGODlhAQABAIAAAP///wAAACH5BAEAAAAALAAAAAABAAEAAAICRAEAOw=='));
        $hybrid->run();

        $this->assertEquals(false, file_exists('./tmp/webtrekk.log'));

        unset($_SERVER['QUERY_STRING']);
    }

    /**
     * @runInSeparateProcess
     */
    public function testQueryString1()
    {
        $_SERVER['QUERY_STRING'] = 'p=601,manieren_per_mausklick,1,1920x1200,24,1,1579682664733,https%3A%2F%2';
        $_SERVER['QUERY_STRING'] .= 'Fwww.google.com%2F,1920x1036,0&eid=2157840760747614879&fns=1&one=0&cg1=ho';
        $_SERVER['QUERY_STRING'] .= 'me&cg15=Manieren%20per%20Mausklick&uc8=0&uc9=0&cs7=non%20paywall%20user&t';
        $_SERVER['QUERY_STRING'] .= 'z=1&pu=http%3A%2F%2Fwww.knigge.de%2F&la=de&eor=1';

        $hybrid = new MappIntelligenceHybrid(array(
            'filename' => MAIN_DIRECTORY . 'tmp/webtrekk.log'
        ));

        $this->expectOutputString(base64_decode('R0lGODlhAQABAIAAAP///wAAACH5BAEAAAAALAAAAAABAAEAAAICRAEAOw=='));
        $hybrid->run();

        $this->assertEquals(true, file_exists(MAIN_DIRECTORY . 'tmp/webtrekk.log'));
        $this->assertEquals(
            'wt?' . $_SERVER['QUERY_STRING'] . "\n",
            file_get_contents(MAIN_DIRECTORY . 'tmp/webtrekk.log')
        );

        unset($_SERVER['QUERY_STRING']);
    }

    /**
     * @runInSeparateProcess
     */
    public function testQueryString2()
    {
        $requestURL = 'https://sub.domain.tld/pix?';
        $queryString = 'p=601,manieren_per_mausklick,1,1920x1200,24,1,1579682664733,https%3A%2F%2';
        $queryString .= 'Fwww.google.com%2F,1920x1036,0&eid=2157840760747614879&fns=1&one=0&cg1=ho';
        $queryString .= 'me&cg15=Manieren%20per%20Mausklick&uc8=0&uc9=0&cs7=non%20paywall%20user&t';
        $queryString .= 'z=1&pu=http%3A%2F%2Fwww.knigge.de%2F&la=de&eor=1';

        $hybrid = new MappIntelligenceHybrid(array(
            'filename' => MAIN_DIRECTORY . 'tmp/webtrekk.log',
            'requestURL' => $requestURL . $queryString
        ));

        $this->expectOutputString(base64_decode('R0lGODlhAQABAIAAAP///wAAACH5BAEAAAAALAAAAAABAAEAAAICRAEAOw=='));
        $hybrid->run();

        $this->assertEquals(true, file_exists(MAIN_DIRECTORY . 'tmp/webtrekk.log'));
        $this->assertEquals('wt?' . $queryString . "\n", file_get_contents(MAIN_DIRECTORY . 'tmp/webtrekk.log'));
    }

    /**
     * @runInSeparateProcess
     */
    public function testQueryString3()
    {
        $requestURL = 'https://sub.domain.tld/pix?';
        $queryString = 'p=601,manieren_per_mausklick,1,1920x1200,24,1,1579682664733,https%3A%2F%2';
        $queryString .= 'Fwww.google.com%2F,1920x1036,0&eid=2157840760747614879&fns=1&one=0&cg1=ho';
        $queryString .= 'me&cg15=Manieren%20per%20Mausklick&uc8=0&uc9=0&cs7=non%20paywall%20user&t';
        $queryString .= 'z=1&pu=http%3A%2F%2Fwww.knigge.de%2F&la=de&eor=1';

        $hybrid = new MappIntelligenceHybrid(array(
            'filename' => MAIN_DIRECTORY . 'tmp/webtrekk.log'
        ));

        $hybrid->setRequestURL($requestURL . $queryString);

        $this->expectOutputString(base64_decode('R0lGODlhAQABAIAAAP///wAAACH5BAEAAAAALAAAAAABAAEAAAICRAEAOw=='));
        $hybrid->run();

        $this->assertEquals(true, file_exists(MAIN_DIRECTORY . 'tmp/webtrekk.log'));
        $this->assertEquals('wt?' . $queryString . "\n", file_get_contents(MAIN_DIRECTORY . 'tmp/webtrekk.log'));
    }
}
