<?php

// @codeCoverageIgnoreStart
require_once __DIR__ . '/MappIntelligenceCLITable.php';
require_once __DIR__ . '/MappIntelligenceCLIException.php';
require_once __DIR__ . '/../MappIntelligenceMessages.php';
require_once __DIR__ . '/../Config/MappIntelligenceDefaultLogger.php';
// @codeCoverageIgnoreEnd

/**
 * Class MappIntelligenceCLIOptions
 */
class MappIntelligenceCLIOptions
{
    const TRACK_ID = 'trackId';
    const TRACK_DOMAIN = 'trackDomain';
    const CONSUMER_TYPE = 'consumerType';
    const CONFIG = 'config';
    const FILENAME = 'filename';
    const FILE_PATH = 'filePath';
    const FILE_PREFIX = 'filePrefix';
    const DEACTIVATE = 'deactivate';
    const LOGGER = 'logger';
    const HELP = 'help';
    const DEBUG = 'debug';
    const VERSION = 'version';

    private static $ARG = ' <arg>';
    private $config;
    private $options = array();
    private $validOptions = array();

    /**
     * MappIntelligenceCLICronjob constructor.
     * @param array $config
     */
    public function __construct($config)
    {
        $this->config = $config;
    }

    /**
     * @param string $short
     * @param string $long
     *
     * @return bool
     */
    private function isOptionValid($short, $long)
    {
        return array_key_exists($short, $this->config) || array_key_exists($long, $this->config);
    }

    /**
     * @return string
     */
    private function getUsage()
    {
        $usage = ' ';
        foreach ($this->options as $option) {
            $usage .= '[--' . $option['long'] . ($option['withArg'] ? self::$ARG : '') . '] ';
        }

        return $usage;
    }

    /**
     * @throws MappIntelligenceCLIException
     */
    public function parse()
    {
        foreach ($this->config as $key => $value) {
            if (!array_key_exists($key, $this->validOptions)) {
                $message = sprintf(MappIntelligenceMessages::$UNSUPPORTED_OPTION, $key, $value);
                throw new MappIntelligenceCLIException($message);
            }
        }
    }

    /**
     * @param string $short
     * @param string $long
     * @param bool $withArg
     * @param string $description
     */
    public function addOption($short = '', $long = '', $withArg = false, $description = '')
    {
        $data = array(
            'short' => $short,
            'long' => $long,
            'withArg' => $withArg,
            'description' => $description
        );

        if (!empty($long)) {
            $this->options[$long] = $data;
            $this->validOptions[$long] = true;
        }

        if (!empty($short)) {
            $this->validOptions[$short] = true;
        }
    }

     /**
      * @param string $name
      *
      * @return bool
      */
    public function hasOption($name)
    {
        return array_key_exists($name, $this->options) && $this->isOptionValid($this->options[$name]['short'], $name);
    }

    /**
     * @param $name
     *
     * @return string
     */
    public function getOptionValue($name)
    {
        $short = $this->options[$name]['short'];
        return ((array_key_exists($short, $this->config)) ? $this->config[$short] : $this->config[$name]);
    }

    /**
     * @param string $message
     */
    public function printCLI($message)
    {
        $l = new MappIntelligenceDefaultLogger();
        $l->log($message);
    }

    /**
     *
     */
    public function printHelp()
    {
        $table = new MappIntelligenceCLITable();

        $table->addRow('Usage:', 2, '', 16);
        $table->addRow('', 1, MappIntelligenceMessages::$HELP_SYNTAX . '' . $this->getUsage(), 17);
        $table->addEmptyRow();
        $table->addRow('', 1, MappIntelligenceMessages::$HELP_HEADER, 17);
        $table->addEmptyRow();
        $table->addRow('Options:', 2, '', 16);

        foreach ($this->options as $option) {
            $line = ' ';

            if (!empty($option['short'])) {
                $line .= '-' . $option['short'] . ', ';
            } else {
                $line .= '    ';
            }

            $line .= '--' . $option['long'];

            if ($option['withArg']) {
                $line .= self::$ARG;
            }

            $table->addRow($line, 7, $option['description'], 11);
        }

        $table->addRow('', 2, MappIntelligenceMessages::$HELP_FOOTER, 16);

        $this->printCLI($table->build());
    }
}
