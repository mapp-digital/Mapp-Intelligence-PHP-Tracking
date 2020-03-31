<?php

require_once __DIR__ . '/MappIntelligenceBasic.php';
require_once __DIR__ . '/../MappIntelligenceVersion.php';

/**
 * Class MappIntelligenceSession
 */
class MappIntelligenceSession extends MappIntelligenceBasic
{
    protected $loginStatus = '';
    protected $pixelVersion = '';
    protected $trackingPlatform = 'PHP';
    protected $parameter = array();

    /**
     * MappIntelligenceSession constructor.
     */
    public function __construct()
    {
        $this->pixelVersion = MappIntelligenceVersion::get();
    }

    /**
     * @return array
     */
    protected function getQueryList()
    {
        return array(
            'loginStatus' => 'cs800',
            'pixelVersion' => 'cs801',
            'trackingPlatform' => 'cs802',
            'parameter' => 'cs'
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
