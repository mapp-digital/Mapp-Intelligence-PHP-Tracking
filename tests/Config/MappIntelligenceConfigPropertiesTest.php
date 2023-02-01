<?php

require_once __DIR__ . '/../MappIntelligenceExtendsTestCase.php';

/**
 * Class MappIntelligenceConfigTest
 */
class MappIntelligenceConfigPropertiesTest extends MappIntelligenceExtendsTestCase
{
    public function testDefaultProperty()
    {
        $props = new MappIntelligenceConfigProperties('');

        $this->assertEquals('', $props->getStringProperty('trackId', ''));
        $this->assertEquals('', $props->getStringProperty('trackDomain', ''));
        $this->assertEquals(0, count($props->getListProperty('domain', array())));
        $this->assertEquals(
            MappIntelligenceConsumerType::CURL,
            $props->getConsumerTypeProperty('consumerType', MappIntelligenceConsumerType::CURL)
        );
        $this->assertEquals('', $props->getStringProperty('filename', ''));
        $this->assertEquals('a', $props->getStringProperty('fileMode', 'a'));
        $this->assertEquals(1, $props->getIntegerProperty('maxAttempt', 1));
        $this->assertEquals(100, $props->getIntegerProperty('attemptTimeout', 100));
        $this->assertEquals(50, $props->getIntegerProperty('maxBatchSize', 50));
        $this->assertEquals(1000, $props->getIntegerProperty('maxQueueSize', 1000));
        $this->assertEquals(true, $props->getBooleanProperty('forceSSL', true));
        $this->assertEquals(0, count($props->getListProperty('useParamsForDefaultPageName', array())));
        $this->assertEquals(0, count($props->getListProperty('containsInclude', array())));
        $this->assertEquals(0, count($props->getListProperty('containsExclude', array())));
        $this->assertEquals(0, count($props->getListProperty('matchesInclude', array())));
        $this->assertEquals(0, count($props->getListProperty('matchesExclude', array())));
    }

    public function testStringProperty()
    {
        $props = new MappIntelligenceConfigProperties('');
        MappIntelligenceUnitHelper::setProperty($props, 'prop', array(
            'trackId' => '111111111111111',
            'trackDomain' => 'analytics01.wt-eu02.net',
            'filename' => '/dev/null',
            'fileMode' => 'x'
        ));

        $this->assertEquals('111111111111111', $props->getStringProperty('trackId', ''));
        $this->assertEquals('analytics01.wt-eu02.net', $props->getStringProperty('trackDomain', ''));
        $this->assertEquals('/dev/null', $props->getStringProperty('filename', ''));
        $this->assertEquals('x', $props->getStringProperty('fileMode', 'a'));
    }

    public function testListProperty()
    {
        $props = new MappIntelligenceConfigProperties('');
        MappIntelligenceUnitHelper::setProperty($props, 'prop', array(
            'domain' => array('www.mapp.com', 'sub.domain.tld'),
            'useParamsForDefaultPageName' => array('foo', 'bar'),
            'containsInclude' => array('foo', 'bar'),
            'containsExclude' => array('test'),
            'matchesInclude' => array('/.*foo.*/', '/.*bar.*/'),
            'matchesExclude' => array('/.*test.*/')
        ));

        $domain = $props->getListProperty('domain', array());
        $this->assertEquals(2, count($domain));
        $this->assertEquals('www.mapp.com', $domain[0]);
        $this->assertEquals('sub.domain.tld', $domain[1]);

        $useParamsForDefaultPageName = $props->getListProperty('useParamsForDefaultPageName', array());
        $this->assertEquals(2, count($useParamsForDefaultPageName));
        $this->assertEquals('foo', $useParamsForDefaultPageName[0]);
        $this->assertEquals('bar', $useParamsForDefaultPageName[1]);

        $containsInclude = $props->getListProperty('containsInclude', array());
        $this->assertEquals(2, count($containsInclude));
        $this->assertEquals('foo', $containsInclude[0]);
        $this->assertEquals('bar', $containsInclude[1]);

        $containsExclude = $props->getListProperty('containsExclude', array());
        $this->assertEquals(1, count($containsExclude));
        $this->assertEquals('test', $containsExclude[0]);

        $matchesInclude = $props->getListProperty('matchesInclude', array());
        $this->assertEquals(2, count($matchesInclude));
        $this->assertEquals('/.*foo.*/', $matchesInclude[0]);
        $this->assertEquals('/.*bar.*/', $matchesInclude[1]);

        $matchesExclude = $props->getListProperty('matchesExclude', array());
        $this->assertEquals(1, count($matchesExclude));
        $this->assertEquals('/.*test.*/', $matchesExclude[0]);
    }

    public function testBooleanProperty1()
    {
        $props = new MappIntelligenceConfigProperties('');
        MappIntelligenceUnitHelper::setProperty($props, 'prop', array(
            'forceSSL' => 'false'
        ));

        $this->assertEquals(false, $props->getBooleanProperty('forceSSL', true));
    }

    public function testBooleanProperty2()
    {
        $props = new MappIntelligenceConfigProperties('');
        MappIntelligenceUnitHelper::setProperty($props, 'prop', array(
            'forceSSL' => '0'
        ));

        $this->assertEquals(false, $props->getBooleanProperty('forceSSL', true));
    }

    public function testIntegerProperty()
    {
        $props = new MappIntelligenceConfigProperties('');
        MappIntelligenceUnitHelper::setProperty($props, 'prop', array(
            'maxAttempt' => '5',
            'attemptTimeout' => '300',
            'maxBatchSize' => '1000',
            'maxQueueSize' => '2500',
        ));

        $this->assertEquals(5, $props->getIntegerProperty('maxAttempt', 1));
        $this->assertEquals(300, $props->getIntegerProperty('attemptTimeout', 100));
        $this->assertEquals(1000, $props->getIntegerProperty('maxBatchSize', 50));
        $this->assertEquals(2500, $props->getIntegerProperty('maxQueueSize', 1000));
    }

    public function testConsumerTypePropertyFile()
    {
        $props = new MappIntelligenceConfigProperties('');
        MappIntelligenceUnitHelper::setProperty($props, 'prop', array(
            'consumerType' => MappIntelligenceConsumerType::FILE
        ));

        $this->assertEquals(
            MappIntelligenceConsumerType::FILE,
            $props->getConsumerTypeProperty('consumerType', MappIntelligenceConsumerType::CURL)
        );
    }

    public function testConsumerTypePropertyCURL()
    {
        $props = new MappIntelligenceConfigProperties('');
        MappIntelligenceUnitHelper::setProperty($props, 'prop', array(
            'consumerType' => MappIntelligenceConsumerType::CURL
        ));

        $this->assertEquals(
            MappIntelligenceConsumerType::CURL,
            $props->getConsumerTypeProperty('consumerType', MappIntelligenceConsumerType::CURL)
        );
    }

    public function testConsumerTypePropertyFormCURL()
    {
        $props = new MappIntelligenceConfigProperties('');
        MappIntelligenceUnitHelper::setProperty($props, 'prop', array(
            'consumerType' => MappIntelligenceConsumerType::FORK_CURL
        ));

        $this->assertEquals(
            MappIntelligenceConsumerType::FORK_CURL,
            $props->getConsumerTypeProperty('consumerType', MappIntelligenceConsumerType::CURL)
        );
    }

    public function testConsumerTypePropertyFileRotation()
    {
        $props = new MappIntelligenceConfigProperties('');
        MappIntelligenceUnitHelper::setProperty($props, 'prop', array(
            'consumerType' => MappIntelligenceConsumerType::FILE_ROTATION
        ));

        $this->assertEquals(
            MappIntelligenceConsumerType::FILE_ROTATION,
            $props->getConsumerTypeProperty('consumerType', MappIntelligenceConsumerType::CURL)
        );
    }

    public function testConsumerTypePropertyCustom()
    {
        $props = new MappIntelligenceConfigProperties('');
        MappIntelligenceUnitHelper::setProperty($props, 'prop', array(
            'consumerType' => MappIntelligenceConsumerType::CUSTOM
        ));

        $this->assertEquals(
            MappIntelligenceConsumerType::CUSTOM,
            $props->getConsumerTypeProperty('consumerType', MappIntelligenceConsumerType::CURL)
        );
    }
}
