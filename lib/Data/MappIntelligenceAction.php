<?php

require_once __DIR__ . '/MappIntelligenceBasic.php';
require_once __DIR__ . '/../MappIntelligenceParameter.php';

/**
 * Class MappIntelligenceAction
 */
class MappIntelligenceAction extends MappIntelligenceBasic
{
    protected $name = '';
    protected $parameter = array();
    protected $goal = array();

    /**
     * MappIntelligenceAction constructor.
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
            'name' => MappIntelligenceParameter::$ACTION_NAME,
            'parameter' => MappIntelligenceParameter::$CUSTOM_ACTION_PARAMETER,
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
    public function setGoal($id, $value)
    {
        $this->goal[$id] = $value;

        return $this;
    }
}
