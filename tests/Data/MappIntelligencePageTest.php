<?php

require_once __DIR__ . '/../MappIntelligenceExtendsTestCase.php';

/**
 * Class MappIntelligencePageTest
 */
class MappIntelligencePageTest extends MappIntelligenceExtendsTestCase
{
    public function testNewPageWithoutName()
    {
        $page = new MappIntelligencePage();

        $data = $page->getData();
        $this->assertEmpty($data['name']);
    }

    public function testNewPageWithName()
    {
        $page = new MappIntelligencePage('foo.bar');

        $data = $page->getData();
        $this->assertEquals('foo.bar', $data['name']);
    }

    public function testGetDefault()
    {
        $page = new MappIntelligencePage();

        $data = $page->getData();
        $this->assertEquals('', $data['name']);
        $this->assertEquals('', $data['search']);
        $this->assertEquals(0, $data['numberSearchResults']);
        $this->assertEquals('', $data['errorMessages']);
        $this->assertEquals(false, $data['paywall']);
        $this->assertEquals('', $data['articleTitle']);
        $this->assertEquals('', $data['contentTags']);
        $this->assertEquals('', $data['title']);
        $this->assertEquals('', $data['type']);
        $this->assertEquals('', $data['length']);
        $this->assertEquals(0, $data['daysSincePublication']);
        $this->assertEquals('', $data['testVariant']);
        $this->assertEquals('', $data['testExperiment']);
        $this->assertEquals(0, count($data['parameter']));
        $this->assertEquals(0, count($data['category']));
        $this->assertEquals(0, count($data['goal']));
    }

    public function testSetTestExperiment()
    {
        $page = new MappIntelligencePage();
        $page->setTestExperiment('name of an experiment');

        $data = $page->getData();
        $this->assertEquals('name of an experiment', $data['testExperiment']);
    }

    public function testSetContentTags()
    {
        $page = new MappIntelligencePage();
        $page->setContentTags('name of a content tag');

        $data = $page->getData();
        $this->assertEquals('name of a content tag', $data['contentTags']);
    }

    public function testSetPaywall()
    {
        $page = new MappIntelligencePage();
        $page->setPaywall(true);

        $data = $page->getData();
        $this->assertEquals(true, $data['paywall']);
    }

    public function testSetLength()
    {
        $page = new MappIntelligencePage();
        $page->setLength('large');

        $data = $page->getData();
        $this->assertEquals('large', $data['length']);
    }

    public function testSetParameter()
    {
        $page = new MappIntelligencePage();
        $page->setParameter(2, 'foo');
        $page->setParameter(15, 'bar');

        $data = $page->getData();
        $this->assertEquals('foo', $data['parameter'][2]);
        $this->assertEquals('bar', $data['parameter'][15]);
    }

    public function testSetDaysSincePublication()
    {
        $page = new MappIntelligencePage();
        $page->setDaysSincePublication(8);

        $data = $page->getData();
        $this->assertEquals(8, $data['daysSincePublication']);
    }

    public function testSetTestVariant()
    {
        $page = new MappIntelligencePage();
        $page->setTestVariant('name of a variant');

        $data = $page->getData();
        $this->assertEquals('name of a variant', $data['testVariant']);
    }

    public function testSetSearch()
    {
        $page = new MappIntelligencePage();
        $page->setSearch('search term');

        $data = $page->getData();
        $this->assertEquals('search term', $data['search']);
    }

    public function testSetArticleTitle()
    {
        $page = new MappIntelligencePage();
        $page->setArticleTitle('name of an article');

        $data = $page->getData();
        $this->assertEquals('name of an article', $data['articleTitle']);
    }

    public function testSetType()
    {
        $page = new MappIntelligencePage();
        $page->setType('type of a page');

        $data = $page->getData();
        $this->assertEquals('type of a page', $data['type']);
    }

    public function testSetErrorMessages()
    {
        $page = new MappIntelligencePage();
        $page->setErrorMessages('error message');

        $data = $page->getData();
        $this->assertEquals('error message', $data['errorMessages']);
    }

    public function testSetName()
    {
        $page = new MappIntelligencePage();
        $page->setName('name of a page');

        $data = $page->getData();
        $this->assertEquals('name of a page', $data['name']);
    }

    public function testSetTitle()
    {
        $page = new MappIntelligencePage();
        $page->setTitle('title of a page');

        $data = $page->getData();
        $this->assertEquals('title of a page', $data['title']);
    }

    public function testSetNumberSearchResults()
    {
        $page = new MappIntelligencePage();
        $page->setNumberSearchResults(15);

        $data = $page->getData();
        $this->assertEquals(15, $data['numberSearchResults']);
    }

    public function testSetCategory()
    {
        $page = new MappIntelligencePage();
        $page->setCategory(2, 'foo');
        $page->setCategory(15, 'bar');

        $data = $page->getData();
        $this->assertEquals('foo', $data['category'][2]);
        $this->assertEquals('bar', $data['category'][15]);
    }

    public function testSetGoal()
    {
        $page = new MappIntelligencePage();
        $page->setGoal(2, 'foo');
        $page->setGoal(15, 'bar');

        $data = $page->getData();
        $this->assertEquals('foo', $data['goal'][2]);
        $this->assertEquals('bar', $data['goal'][15]);
    }

    public function testGetQueryParameter()
    {
        $page = new MappIntelligencePage();
        $page
            ->setTestExperiment('name of an experiment')
            ->setContentTags('name of a content tag')
            ->setPaywall(true)
            ->setLength('large')
            ->setDaysSincePublication(8)
            ->setTestVariant('name of a variant')
            ->setSearch('search term')
            ->setArticleTitle('name of an article')
            ->setType('type of a page')
            ->setErrorMessages('error message')
            ->setName('name of a page')
            ->setTitle('title of a page')
            ->setNumberSearchResults(15)
            ->setParameter(2, 'parameter 2')
            ->setParameter(15, 'parameter 15')
            ->setCategory(2, 'category 2')
            ->setCategory(15, 'category 15')
            ->setGoal(2, 'goal 2')
            ->setGoal(15, 'goal 15');

        $data = $page->getQueryParameter();
        $this->assertEquals('name of a page', $data['pn']);
        $this->assertEquals('search term', $data['is']);
        $this->assertEquals(15, $data['cp771']);
        $this->assertEquals('error message', $data['cp772']);
        $this->assertEquals(true, $data['cp773']);
        $this->assertEquals('name of an article', $data['cp774']);
        $this->assertEquals('name of a content tag', $data['cp775']);
        $this->assertEquals('title of a page', $data['cp776']);
        $this->assertEquals('type of a page', $data['cp777']);
        $this->assertEquals('large', $data['cp778']);
        $this->assertEquals(8, $data['cp779']);
        $this->assertEquals('name of a variant', $data['cp781']);
        $this->assertEquals('name of an experiment', $data['cp782']);
        $this->assertEquals('parameter 2', $data['cp2']);
        $this->assertEquals('parameter 15', $data['cp15']);
        $this->assertEquals('category 2', $data['cg2']);
        $this->assertEquals('category 15', $data['cg15']);
        $this->assertEquals('goal 2', $data['cb2']);
        $this->assertEquals('goal 15', $data['cb15']);
    }

    public function testGetDefaultQueryParameter()
    {
        $page = new MappIntelligencePage();

        $data = $page->getQueryParameter();
        $this->assertEquals(0, count($data));
    }
}
