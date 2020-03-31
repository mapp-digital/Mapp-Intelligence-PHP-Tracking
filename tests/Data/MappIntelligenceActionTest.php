<?php

/**
 * Class MappIntelligenceActionTest
 */
class MappIntelligenceActionTest extends PHPUnit\Framework\TestCase
{
    public function testNewActionWithoutName()
    {
        $action = new MappIntelligenceAction();

        $data = $action->getData();
        $this->assertEmpty($data['name']);
    }

    public function testNewActionWithName()
    {
        $action = new MappIntelligenceAction('foo.bar');

        $data = $action->getData();
        $this->assertEquals('foo.bar', $data['name']);
    }

    public function testGetDefault()
    {
        $action = new MappIntelligenceAction();

        $data = $action->getData();
        $this->assertEquals('', $data['name']);
        $this->assertEquals(0, count($data['parameter']));
        $this->assertEquals(0, count($data['goal']));
    }

    public function testSetName()
    {
        $action = new MappIntelligenceAction('foo.bar');
        $action->setName('bar.foo');

        $data = $action->getData();
        $this->assertEquals('bar.foo', $data['name']);
    }

    public function testSetGoal()
    {
        $action = new MappIntelligenceAction();
        $action
            ->setGoal(2, 'foo')
            ->setGoal(15, 'bar');

        $data = $action->getData();
        $this->assertEquals('foo', $data['goal'][2]);
        $this->assertEquals('bar', $data['goal'][15]);
    }

    public function testSetParameter()
    {
        $action = new MappIntelligenceAction();
        $action
            ->setParameter(2, 'foo')
            ->setParameter(15, 'bar');

        $data = $action->getData();
        $this->assertEquals('foo', $data['parameter'][2]);
        $this->assertEquals('bar', $data['parameter'][15]);
    }

    public function testGetQueryParameter()
    {
        $action = new MappIntelligenceAction();
        $action
            ->setName('foo.bar')
            ->setParameter(2, 'param2')
            ->setParameter(15, 'param15')
            ->setGoal(2, 'goal2')
            ->setGoal(15, 'goal15');

        $data = $action->getQueryParameter();
        $this->assertEquals('foo.bar', $data['ct']);
        $this->assertEquals('param2', $data['ck2']);
        $this->assertEquals('param15', $data['ck15']);
        $this->assertEquals('goal2', $data['cb2']);
        $this->assertEquals('goal15', $data['cb15']);
    }

    public function testGetDefaultQueryParameter()
    {
        $action = new MappIntelligenceAction();

        $data = $action->getQueryParameter();
        $this->assertEquals(0, count($data));
    }
}
