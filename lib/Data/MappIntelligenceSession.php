<?php

require_once __DIR__ . '/MappIntelligenceBasic.php';
require_once __DIR__ . '/../MappIntelligenceParameter.php';

/**
 * Class MappIntelligenceSession
 */
class MappIntelligenceSession extends MappIntelligenceBasic
{
    const TEMPORARY_SESSION_ID_TYPE = '2.0.0';

    protected $loginStatus = '';
    protected $temporarySessionId = '';
    protected $temporarySessionIdType = '';
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
            'temporarySessionId' => MappIntelligenceParameter::$TEMPORARY_SESSION_ID,
            'temporarySessionIdType' => MappIntelligenceParameter::$TEMPORARY_SESSION_ID_TYPE,
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
     * @param string $tSessionId
     *
     * @return $this
     */
    public function setTemporarySessionId($tSessionId)
    {
        $this->temporarySessionId = $tSessionId;
        $this->temporarySessionIdType = self::TEMPORARY_SESSION_ID_TYPE;

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
