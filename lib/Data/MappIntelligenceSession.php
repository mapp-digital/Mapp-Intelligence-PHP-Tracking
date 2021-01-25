<?php

require_once __DIR__ . '/MappIntelligenceBasic.php';
require_once __DIR__ . '/../MappIntelligenceParameter.php';

/**
 * Class MappIntelligenceSession
 */
class MappIntelligenceSession extends MappIntelligenceBasic
{
    protected $loginStatus = '';
    protected $parameter = array();

    /**
     * MappIntelligenceSession constructor.
     */
    public function __construct()
    {
        // do nothing
    }

    /**
     * @return array
     */
    protected function getQueryList()
    {
        return array(
            'loginStatus' => MappIntelligenceParameter::$LOGIN_STATUS,
            'parameter' => MappIntelligenceParameter::$CUSTOM_SESSION_PARAMETER
        );
    }

    /**
     * @param string $loginStatus
     *
     * @return $this
     */
    public function setLoginStatus($loginStatus)
    {
        $this->loginStatus = $loginStatus;

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
