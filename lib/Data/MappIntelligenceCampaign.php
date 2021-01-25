<?php

require_once __DIR__ . '/MappIntelligenceBasic.php';
require_once __DIR__ . '/../MappIntelligenceParameter.php';

/**
 * Class MappIntelligenceCampaign
 */
class MappIntelligenceCampaign extends MappIntelligenceBasic
{
    protected $id = '';
    protected $action = 'c';
    protected $mediaCode = array('mc', 'wt_mc');
    protected $oncePerSession = false;
    protected $parameter = array();

    /**
     * MappIntelligenceCampaign constructor.
     * @param string $id
     */
    public function __construct($id = '')
    {
        $this->id = $id;
    }

    /**
     * @return array
     */
    protected function getQueryList()
    {
        return array(
            'id' => MappIntelligenceParameter::$CAMPAIGN_ID,
            'action' => MappIntelligenceParameter::$CAMPAIGN_ACTION,
            'parameter' => MappIntelligenceParameter::$CUSTOM_CAMPAIGN_PARAMETER
        );
    }

    /**
     * @param string $id
     *
     * @return $this
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @param array $mediaCode
     *
     * @return $this
     */
    public function setMediaCode($mediaCode)
    {
        $this->mediaCode = $mediaCode;

        return $this;
    }

    /**
     * @param bool $oncePerSession
     *
     * @return $this
     */
    public function setOncePerSession($oncePerSession)
    {
        $this->oncePerSession = $oncePerSession;

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
}
