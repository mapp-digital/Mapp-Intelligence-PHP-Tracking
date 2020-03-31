<?php

require_once __DIR__ . '/Queue/MappIntelligenceQueue.php';

/**
 * Class MappIntelligenceServer2Server
 */
class MappIntelligenceServer2Server
{
    private $options;

    /**
     * MappIntelligenceServer2Server constructor.
     * @param array $opt
     * @throws Exception
     */
    public function __construct($opt)
    {
        $args = $opt;

        if (!$this->isOptionInvalid('c', 'config', $args)) {
            $file = ((array_key_exists('c', $args)) ? $args['c'] : $args['config']);
            if (file_exists($file)) {
                $configFile = parse_ini_file($file);
                $args = array_merge($configFile, $args);
            } else {
                throw new Exception('config file "' . $file . '" not found');
            }
        }

        if ($this->isOptionInvalid('f', 'filename', $args)) {
            $args['filename'] = sys_get_temp_dir() . '/MappIntelligenceRequests.log';
        }

        $debug = false;
        if (array_key_exists('debug', $args)) {
            $debug = array_key_exists('debug', $args);
        }

        $deactivate = false;
        if (array_key_exists('deactivate', $args)) {
            $deactivate = array_key_exists('deactivate', $args);
        }

        $options = array(
            'consumer' => 'file',
            'filename' => ((array_key_exists('f', $args)) ? $args['f'] : $args['filename']),
            'deactivate' => $deactivate,
            'debug' => $debug,
        );

        $this->options = $options;
    }

    /**
     * @param string $short
     * @param string $long
     * @param array $options
     * @return bool
     */
    private function isOptionInvalid($short, $long, $options)
    {
        return (
            (!array_key_exists($short, $options) || !is_string($options[$short]))
            && (!array_key_exists($long, $options) || !is_string($options[$long]))
        );
    }

    /**
     * @param boolean $withoutResponse
     */
    public function run($withoutResponse = false)
    {
        if (!$this->options['deactivate'] && array_key_exists('QUERY_STRING', $_SERVER) && $_SERVER['QUERY_STRING']) {
            $mapp = new MappIntelligenceQueue($this->options);
            $mapp->add('wt?' . $_SERVER['QUERY_STRING']);
            $mapp->flush();
        }

        if (!$withoutResponse) {
            header('Content-Type: image/gif');
            header('Content-Length: 43');

            // ToDo: use 'gzencode' and 'Content-Encoding: gzip'
            echo base64_decode('R0lGODlhAQABAIAAAP///wAAACH5BAEAAAAALAAAAAABAAEAAAICRAEAOw==');
        }
    }
}
