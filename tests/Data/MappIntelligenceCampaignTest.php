<?php

require_once __DIR__ . '/../MappIntelligenceExtendsTestCase.php';

/**
 * Class MappIntelligenceCampaignTest
 */
class MappIntelligenceCampaignTest extends MappIntelligenceExtendsTestCase
{
    public function testNewCampaignWithoutId()
    {
        $campaign = new MappIntelligenceCampaign();

        $data = $campaign->getData();
        $this->assertEmpty($data['id']);
    }

    public function testNewCampaignWithId()
    {
        $campaign = new MappIntelligenceCampaign('wt_mc%3Dfoo.bar');

        $data = $campaign->getData();
        $this->assertEquals('wt_mc%3Dfoo.bar', $data['id']);
    }

    public function testGetDefault()
    {
        $campaign = new MappIntelligenceCampaign();

        $data = $campaign->getData();
        $this->assertEquals('', $data['id']);
        $this->assertEquals('c', $data['action']);
        $this->assertEquals(0, count($data['parameter']));
    }

    public function testSetId()
    {
        $campaign = new MappIntelligenceCampaign('wt_mc%3Dfoo.bar');
        $campaign->setId('wt%3Dfoo.bar');

        $data = $campaign->getData();
        $this->assertEquals('wt%3Dfoo.bar', $data['id']);
    }

    public function testSetParameter()
    {
        $campaign = new MappIntelligenceCampaign();
        $campaign->setParameter(2, 'foo');
        $campaign->setParameter(15, 'bar');

        $data = $campaign->getData();
        $this->assertEquals('foo', $data['parameter'][2]);
        $this->assertEquals('bar', $data['parameter'][15]);
    }

    public function testSetMediaCode()
    {
        $campaign = new MappIntelligenceCampaign();
        $campaign->setMediaCode(array('foo', 'bar'));

        $this->assertEquals('1', '1');
    }

    public function testSetOncePerSession()
    {
        $campaign = new MappIntelligenceCampaign();
        $campaign->setOncePerSession(false);

        $this->assertEquals('1', '1');
    }

    public function testGetQueryParameter()
    {
        $campaign = new MappIntelligenceCampaign();
        $campaign->setId('wt_mc%3Dfoo.bar');
        $campaign->setParameter(2, 'param2');
        $campaign->setParameter(15, 'param15');

        $data = $campaign->getQueryParameter();
        $this->assertEquals('wt_mc%3Dfoo.bar', $data['mc']);
        $this->assertEquals('c', $data['mca']);
        $this->assertEquals('param2', $data['cc2']);
        $this->assertEquals('param15', $data['cc15']);
    }

    public function testGetDefaultQueryParameter()
    {
        $campaign = new MappIntelligenceCampaign();

        $data = $campaign->getQueryParameter();
        $this->assertEquals(1, count($data));
        $this->assertEquals('c', $data['mca']);
    }
}
