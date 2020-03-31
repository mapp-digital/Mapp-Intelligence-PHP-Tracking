<?php

/**
 * Class MappIntelligenceConfig
 */
class MappIntelligenceConfig
{
    /**
     * @var array
     */
    private $defaultConfig_ = array(
        'trackId' => '',
        'trackDomain' => '',
        'domain' => array(),
        'deactivate' => false,

        'debug' => false,

        'consumer' => 'curl',
        'filename' => false,
        'fileMode' => 'a',
        'maxAttempt' => 1,
        'attemptTimeout' => 100,
        'maxBatchSize' => 50,
        'maxQueueSize' => 1000,
        'forceSSL' => true,

        'useParamsForDefaultPageName' => array()
    );

    /**
     * @var array
     */
    protected $config_;

    /**
     * MappIntelligenceConfig constructor.
     * @param array $config
     */
    protected function __construct(array $config = array())
    {
        if (array_key_exists('config', $config) && file_exists($config['config'])) {
            $configFile = parse_ini_file($config['config']);
            $config = array_merge($configFile, $config);
        }

        $config = array_merge($this->defaultConfig_, $config);
        $this->config_ = $config;

        if (!$this->config_['domain'] || !is_array($this->config_['domain']) || count($this->config_['domain']) <= 0) {
            $this->config_['domain'] = array(
                $this->getOwnDomain()
            );
        }

        if (!is_string($this->config_['filename']) || !$this->config_['filename']) {
            $this->config_['filename'] = sys_get_temp_dir() . '/MappIntelligenceRequests.log';
        }

        if ($this->config_['consumer'] === 'file') {
            if ($this->config_['fileMode'] !== 'a' && $this->config_['fileMode'] !== 'c') {
                $this->config_['fileMode'] = 'a';
            }

            $this->config_['maxBatchSize'] = 1;
        }

        if ($this->config_['maxAttempt'] < 1 || $this->config_['maxAttempt'] > 5) {
            $this->config_['maxAttempt'] = 1;
        }

        if ($this->config_['attemptTimeout'] < 1 || $this->config_['attemptTimeout'] > 500) {
            $this->config_['attemptTimeout'] = 200;
        }
    }

    /**
     * @param $msg
     */
    protected function log($msg)
    {
        if ($this->config_['debug']) {
            error_log("[Mapp Intelligence]: " . $msg);
        }
    }

    /**
     * @return string
     */
    private function getOwnDomain()
    {
        return array_key_exists('HTTP_HOST', $_SERVER) ? $_SERVER['HTTP_HOST'] : '';
    }
}
