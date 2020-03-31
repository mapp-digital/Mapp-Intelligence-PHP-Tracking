<?php

/**
 * Class MappIntelligenceSessionTest
 */
class MappIntelligenceSessionTest extends PHPUnit\Framework\TestCase
{
    public function testGetDefault()
    {
        $session = new MappIntelligenceSession();

        $data = $session->getData();
        $this->assertEquals('', $data['loginStatus']);
        $this->assertEquals(MappIntelligenceVersion::get(), $data['pixelVersion']);
        $this->assertEquals('PHP', $data['trackingPlatform']);
        $this->assertEquals(0, count($data['parameter']));
    }

    public function testSetParameter()
    {
        $session = new MappIntelligenceSession();
        $session->setParameter(2, 'foo');
        $session->setParameter(15, 'bar');

        $data = $session->getData();
        $this->assertEquals('foo', $data['parameter'][2]);
        $this->assertEquals('bar', $data['parameter'][15]);
    }

    public function testSetLoginStatus()
    {
        $session = new MappIntelligenceSession();
        $session->setLoginStatus('logged in');

        $data = $session->getData();
        $this->assertEquals('logged in', $data['loginStatus']);
    }

    public function testGetQueryParameter()
    {
        $session = new MappIntelligenceSession();
        $session
            ->setLoginStatus('logged in')
            ->setParameter(2, 'param2')
            ->setParameter(15, 'param15');

        $data = $session->getQueryParameter();
        $this->assertEquals('logged in', $data['cs800']);
        $this->assertEquals(MappIntelligenceVersion::get(), $data['cs801']);
        $this->assertEquals('PHP', $data['cs802']);
        $this->assertEquals('param2', $data['cs2']);
        $this->assertEquals('param15', $data['cs15']);
    }

    public function testGetDefaultQueryParameter()
    {
        $session = new MappIntelligenceSession();

        $data = $session->getQueryParameter();
        $this->assertEquals(2, count($data));
        $this->assertEquals(MappIntelligenceVersion::get(), $data['cs801']);
        $this->assertEquals('PHP', $data['cs802']);
    }
}
