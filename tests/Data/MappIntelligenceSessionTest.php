<?php

require_once __DIR__ . '/../MappIntelligenceExtendsTestCase.php';

/**
 * Class MappIntelligenceSessionTest
 */
class MappIntelligenceSessionTest extends MappIntelligenceExtendsTestCase
{
    public function testGetDefault()
    {
        $session = new MappIntelligenceSession();

        $data = $session->getData();
        $this->assertEquals('', $data['loginStatus']);
        $this->assertEquals('', $data['temporarySessionId']);
        $this->assertEquals('', $data['temporarySessionIdType']);
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

    public function testSetTemporarySessionId()
    {
        $session = new MappIntelligenceSession();
        $session->setTemporarySessionId('abc123');

        $data = $session->getData();
        $this->assertEquals('abc123', $data['temporarySessionId']);
        $this->assertEquals('2.0.0', $data['temporarySessionIdType']);
    }

    public function testGetQueryParameter()
    {
        $session = new MappIntelligenceSession();
        $session
            ->setLoginStatus('logged in')
            ->setTemporarySessionId("abc123")
            ->setParameter(2, 'param2')
            ->setParameter(15, 'param15');

        $data = $session->getQueryParameter();
        $this->assertEquals('logged in', $data['cs800']);
        $this->assertEquals('abc123', $data['fpv']);
        $this->assertEquals('2.0.0', $data['fpt']);
        $this->assertEquals('param2', $data['cs2']);
        $this->assertEquals('param15', $data['cs15']);
    }

    public function testGetDefaultQueryParameter()
    {
        $session = new MappIntelligenceSession();

        $data = $session->getQueryParameter();
        $this->assertEquals(0, count($data));
    }
}
