<?php

require_once __DIR__ . '/MappIntelligenceBasic.php';
require_once __DIR__ . '/../MappIntelligenceParameter.php';

/**
 * Class MappIntelligencePage
 */
class MappIntelligencePage extends MappIntelligenceBasic
{
    protected $name = '';
    protected $search = '';
    protected $numberSearchResults = 0;
    protected $errorMessages = '';
    protected $paywall = false;
    protected $articleTitle = '';
    protected $contentTags = '';
    protected $title = '';
    protected $type = '';
    protected $length = '';
    protected $daysSincePublication = 0;
    protected $testVariant = '';
    protected $testExperiment = '';
    protected $parameter = array();
    protected $category = array();
    protected $goal = array();

    /**
     * MappIntelligencePage constructor.
     * @param string $name
     */
    public function __construct($name = '')
    {
        $this->name = $name;
    }

    /**
     * @return array
     */
    protected function getQueryList()
    {
        return array(
            'name' => MappIntelligenceParameter::$PAGE_NAME,
            'search' => MappIntelligenceParameter::$SEARCH,
            'numberSearchResults' => MappIntelligenceParameter::$NUMBER_SEARCH_RESULTS,
            'errorMessages' => MappIntelligenceParameter::$ERROR_MESSAGES,
            'paywall' => MappIntelligenceParameter::$PAYWALL,
            'articleTitle' => MappIntelligenceParameter::$ARTICLE_TITLE,
            'contentTags' => MappIntelligenceParameter::$CONTENT_TAGS,
            'title' => MappIntelligenceParameter::$PAGE_TITLE,
            'type' => MappIntelligenceParameter::$PAGE_TYPE,
            'length' => MappIntelligenceParameter::$PAGE_LENGTH,
            'daysSincePublication' => MappIntelligenceParameter::$DAYS_SINCE_PUBLICATION,
            'testVariant' => MappIntelligenceParameter::$TEST_VARIANT,
            'testExperiment' => MappIntelligenceParameter::$TEST_EXPERIMENT,
            'parameter' => MappIntelligenceParameter::$CUSTOM_PAGE_PARAMETER,
            'category' => MappIntelligenceParameter::$CUSTOM_PAGE_CATEGORY,
            'goal' => MappIntelligenceParameter::$CUSTOM_PRODUCT_PARAMETER
        );
    }

    /**
     * @param string $name
     *
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @param string $search
     *
     * @return $this
     */
    public function setSearch($search)
    {
        $this->search = $search;

        return $this;
    }

    /**
     * @param int $numberSearchResults
     *
     * @return $this
     */
    public function setNumberSearchResults($numberSearchResults)
    {
        $this->numberSearchResults = $numberSearchResults;

        return $this;
    }

    /**
     * @param string $errorMessages
     *
     * @return $this
     */
    public function setErrorMessages($errorMessages)
    {
        $this->errorMessages = $errorMessages;

        return $this;
    }

    /**
     * @param bool $paywall
     *
     * @return $this
     */
    public function setPaywall($paywall)
    {
        $this->paywall = $paywall;

        return $this;
    }

    /**
     * @param string $articleTitle
     *
     * @return $this
     */
    public function setArticleTitle($articleTitle)
    {
        $this->articleTitle = $articleTitle;

        return $this;
    }

    /**
     * @param string $contentTags
     *
     * @return $this
     */
    public function setContentTags($contentTags)
    {
        $this->contentTags = $contentTags;

        return $this;
    }

    /**
     * @param string $title
     *
     * @return $this
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @param string $type
     *
     * @return $this
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @param string $length
     *
     * @return $this
     */
    public function setLength($length)
    {
        $this->length = $length;

        return $this;
    }

    /**
     * @param int $daysSincePublication
     *
     * @return $this
     */
    public function setDaysSincePublication($daysSincePublication)
    {
        $this->daysSincePublication = $daysSincePublication;

        return $this;
    }

    /**
     * @param string $testExperiment
     *
     * @return $this
     */
    public function setTestExperiment($testExperiment)
    {
        $this->testExperiment = $testExperiment;

        return $this;
    }

    /**
     * @param string $testVariant
     *
     * @return $this
     */
    public function setTestVariant($testVariant)
    {
        $this->testVariant = $testVariant;

        return $this;
    }

    /**
     * @param int $id
     * @param string $value
     *
     * @return $this
     */
    public function setParameter($id, $value)
    {
        $this->parameter[$id] = $value;

        return $this;
    }

    /**
     * @param int $id
     * @param string $value
     *
     * @return $this
     */
    public function setCategory($id, $value)
    {
        $this->category[$id] = $value;

        return $this;
    }

    /**
     * @param int $id
     * @param string $value
     *
     * @return $this
     */
    public function setGoal($id, $value)
    {
        $this->goal[$id] = $value;

        return $this;
    }
}
