<?php

require_once __DIR__ . '/MappIntelligenceExtendsTestCase.php';

/**
 * Class MappIntelligenceLogLevelTest
 */
class MappIntelligenceLogLevelTest extends MappIntelligenceExtendsTestCase
{
    public function testGetNameError()
    {
        $this->assertEquals("ERROR", MappIntelligenceLogLevel::getName(2));
    }

    public function testGetNameDebug()
    {
        $this->assertEquals("DEBUG", MappIntelligenceLogLevel::getName(5));
    }

    public function testGetNameNotFound()
    {
        $this->assertNull(MappIntelligenceLogLevel::getName(6));
    }

    public function testGetValueError()
    {
        $this->assertEquals(2, MappIntelligenceLogLevel::getValue("ERROR"));
    }

    public function testGetValueDebug()
    {
        $this->assertEquals(5, MappIntelligenceLogLevel::getValue("debug"));
    }

    public function testGetValueNotFound()
    {
        $this->assertEquals(-1, MappIntelligenceLogLevel::getValue("FAILED"));
    }
}
