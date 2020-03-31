<?php

/**
 * Class MappIntelligenceCronjobTest
 */
class MappIntelligenceServer2ServerTest extends PHPUnit\Framework\TestCase
{
    public function testConfigFileNotFound()
    {
        $this->expectExceptionMessage('config file "./tmp/config.ini" not found');
        new MappIntelligenceServer2Server(array(
            'c' => './tmp/config.ini'
        ));
    }

    public function testDefaultConfig()
    {
        $s2s = new MappIntelligenceServer2Server(array());

        $options = MappIntelligenceUnitUtil::getProperty($s2s, 'options');
        $this->assertEquals('file', $options['consumer']);
        $this->assertEquals(sys_get_temp_dir() . '/MappIntelligenceRequests.log', $options['filename']);
    }

    public function testConfigFile()
    {
        $s2s = new MappIntelligenceServer2Server(array(
            'c' => './config.ini'
        ));

        $options = MappIntelligenceUnitUtil::getProperty($s2s, 'options');
        $this->assertEquals('file', $options['consumer']);
        $this->assertEquals('', $options['filename']);
    }

    public function testOwnLogFileName()
    {
        $s2s = new MappIntelligenceServer2Server(array(
            'f' => './tmp/webtrekk.log'
        ));

        $options = MappIntelligenceUnitUtil::getProperty($s2s, 'options');
        $this->assertEquals('./tmp/webtrekk.log', $options['filename']);
    }

    /**
     * @runInSeparateProcess
     */
    public function testQueryStringNotDefined()
    {
        $s2s = new MappIntelligenceServer2Server(array(
            'f' => './tmp/webtrekk.log'
        ));

        $this->expectOutputString(base64_decode('R0lGODlhAQABAIAAAP///wAAACH5BAEAAAAALAAAAAABAAEAAAICRAEAOw=='));
        $s2s->run();

        $this->assertEquals(false, file_exists('./tmp/webtrekk.log'));
    }

    /**
     * @runInSeparateProcess
     */
    public function testEmptyQueryString()
    {
        $_SERVER['QUERY_STRING'] = '';

        $s2s = new MappIntelligenceServer2Server(array(
            'f' => './tmp/webtrekk.log'
        ));

        $this->expectOutputString(base64_decode('R0lGODlhAQABAIAAAP///wAAACH5BAEAAAAALAAAAAABAAEAAAICRAEAOw=='));
        $s2s->run();

        $this->assertEquals(false, file_exists('./tmp/webtrekk.log'));

        unset($_SERVER['QUERY_STRING']);
    }

    /**
     * @runInSeparateProcess
     */
    public function testQueryString()
    {
        $_SERVER['QUERY_STRING'] = 'p=601,manieren_per_mausklick,1,1920x1200,24,1,1579682664733,https%3A%2F%2';
        $_SERVER['QUERY_STRING'] .= 'Fwww.google.com%2F,1920x1036,0&eid=2157840760747614879&fns=1&one=0&cg1=ho';
        $_SERVER['QUERY_STRING'] .= 'me&cg15=Manieren%20per%20Mausklick&uc8=0&uc9=0&cs7=non%20paywall%20user&t';
        $_SERVER['QUERY_STRING'] .= 'z=1&pu=http%3A%2F%2Fwww.knigge.de%2F&la=de&eor=1';

        $s2s = new MappIntelligenceServer2Server(array(
            'f' => './tmp/webtrekk.log'
        ));

        $this->expectOutputString(base64_decode('R0lGODlhAQABAIAAAP///wAAACH5BAEAAAAALAAAAAABAAEAAAICRAEAOw=='));
        $s2s->run();

        $this->assertEquals(true, file_exists('./tmp/webtrekk.log'));
        $this->assertEquals('wt?' . $_SERVER['QUERY_STRING'] . "\n", file_get_contents('./tmp/webtrekk.log'));

        unset($_SERVER['QUERY_STRING']);
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

        $s2s = new MappIntelligenceServer2Server(array(
            'f' => './tmp/webtrekk.log',
            'deactivate' => true
        ));

        $this->expectOutputString(base64_decode('R0lGODlhAQABAIAAAP///wAAACH5BAEAAAAALAAAAAABAAEAAAICRAEAOw=='));
        $s2s->run();

        $this->assertEquals(false, file_exists('./tmp/webtrekk.log'));

        unset($_SERVER['QUERY_STRING']);
    }
}
