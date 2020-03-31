<?php

require_once __DIR__ . '/Queue/MappIntelligenceQueue.php';

/**
 * Class MappIntelligenceCronjob
 */
class MappIntelligenceCronjob
{
    private $options;

    /**
     * MappIntelligenceCronjob constructor.
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

        if ($this->isOptionInvalid('i', 'trackId', $args)) {
            throw new Exception('argument "-i" or alternative "--trackId" are required');
        }

        if ($this->isOptionInvalid('d', 'trackDomain', $args)) {
            throw new Exception('argument "-d" or alternative "--trackDomain" are required');
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
            'trackId' => ((array_key_exists('i', $args)) ? $args['i'] : $args['trackId']),
            'trackDomain' => ((array_key_exists('d', $args)) ? $args['d'] : $args['trackDomain']),
            'filename' => ((array_key_exists('f', $args)) ? $args['f'] : $args['filename']),

            'deactivate' => $deactivate,
            'debug' => $debug,

            'maxBatchSize' => 1000,
            'maxQueueSize' => 100000
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
     * @return int|string
     */
    public function run()
    {
        if ($this->options['deactivate']) {
            return 'Mapp Intelligence tracking is deactivated';
        }

        if (!file_exists($this->options['filename'])) {
            return 'request logfile "' . $this->options['filename'] . '" not found';
        }

        $tmpFilename = dirname($this->options['filename']) . '/MappIntelligenceRequests-' . rand() . '.log';
        $renamingError = 'renaming from "' . $this->options['filename'] . '" to "' . $tmpFilename . '" failed';
        try {
            if (!rename($this->options['filename'], $tmpFilename)) {
                throw new Exception($renamingError);
            }
        } catch (Exception $e) {
            return $renamingError;
        }

        $fileContent = file_get_contents($tmpFilename);
        $fileLines = explode("\n", $fileContent);

        $requestQueue = new MappIntelligenceQueue($this->options);
        for ($i = 0, $j = count($fileLines); $i < $j; $i++) {
            if (!trim($fileLines[$i])) {
                continue;
            }

            $requestQueue->add($fileLines[$i]);
        }

        $status = 0;
        if (!$requestQueue->flush()) {
            $this->options['consumer'] = 'file';
            $this->options['fileMode'] = 'c';
            $fileQueue = new MappIntelligenceQueue($this->options);
            $requests = $requestQueue->getQueue();
            for ($i = 0, $j = count($requests); $i < $j; $i++) {
                $fileQueue->add($requests[$i]);
            }

            $status = 1;
        }

        unlink($tmpFilename);

        return $status;
    }
}
