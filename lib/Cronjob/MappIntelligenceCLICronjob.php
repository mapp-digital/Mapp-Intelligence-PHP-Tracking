<?php

// @codeCoverageIgnoreStart
require_once __DIR__ . '/MappIntelligenceCLIException.php';
require_once __DIR__ . '/MappIntelligenceCLIOptions.php';
require_once __DIR__ . '/MappIntelligenceCLIFileTransmitter.php';
require_once __DIR__ . '/MappIntelligenceCLIFileRotationTransmitter.php';
require_once __DIR__ . '/../MappIntelligenceMessages.php';
require_once __DIR__ . '/../MappIntelligenceVersion.php';
require_once __DIR__ . '/../Config/MappIntelligenceConfig.php';
require_once __DIR__ . '/../Consumer/MappIntelligenceConsumerType.php';
// @codeCoverageIgnoreEnd

/**
 * Class MappIntelligenceCLICronjob
 */
class MappIntelligenceCLICronjob
{
    /**
     * Constant for exit status successful.
     */
    const EXIT_STATUS_SUCCESS = 0;

    /**
     * Mapp Intelligence config map
     */
    protected $cfg;
    /**
     * The consumer type to use for data transfer to Intelligence.
     */
    private $consumerType;
    /**
     * The path to your request logging file.
     */
    private $filename;
    /**
     * The path to your request logging files.
     */
    private $filePath;
    /**
     * The prefix name for your request logging file.
     */
    private $filePrefix;
    /**
     * Deactivate the tracking functionality.
     */
    private $deactivate;
    /**
     * Activates the debug mode.
     */
    private $logger;

    /**
     * MappIntelligenceCLICronjob constructor.
     * @param MappIntelligenceConfig|array $config
     * @throws MappIntelligenceCLIException
     */
    public function __construct($config = array())
    {
        if (is_array($config)) {
            $config = $this->compareConfig($config);
        }

        $mappIntelligenceConfig = new MappIntelligenceConfig($config);
        $this->cfg = $mappIntelligenceConfig->build();

        $this->validateOptions();

        $this->consumerType = $this->cfg[MappIntelligenceCLIOptions::CONSUMER_TYPE];
        $this->filename = $this->cfg[MappIntelligenceCLIOptions::FILENAME];
        $this->filePath = $this->cfg[MappIntelligenceCLIOptions::FILE_PATH];
        $this->filePrefix = $this->cfg[MappIntelligenceCLIOptions::FILE_PREFIX];
        $this->deactivate = $this->cfg[MappIntelligenceCLIOptions::DEACTIVATE];
        $this->logger = $this->cfg[MappIntelligenceCLIOptions::LOGGER];

        $this->cfg['consumerType'] = MappIntelligenceConsumerType::CURL;
        $this->cfg['deactivate'] = $this->deactivate;
        $this->cfg['maxBatchSize'] = 1000;
        $this->cfg['maxQueueSize'] = 100000;
    }

    /**
     * @param array $o Object
     *
     * @return boolean
     */
    private function isOptionInvalid($o)
    {
        return empty($o);
    }

    /**
     * @param $config
     *
     * @return MappIntelligenceCLIOptions
     */
    private function getOptions($config)
    {
        $options = new MappIntelligenceCLIOptions($config);

        $options->addOption('i', $options::TRACK_ID, true, MappIntelligenceMessages::$OPTION_TRACK_ID);
        $options->addOption('d', $options::TRACK_DOMAIN, true, MappIntelligenceMessages::$OPTION_TRACK_DOMAIN);
        $options->addOption('t', $options::CONSUMER_TYPE, true, MappIntelligenceMessages::$OPTION_CONSUMER_TYPE);
        $options->addOption('c', $options::CONFIG, true, MappIntelligenceMessages::$OPTION_CONFIG);

        // relevant for file consumer type FILE
        $options->addOption('f', $options::FILENAME, true, MappIntelligenceMessages::$OPTION_FILENAME);

        // relevant for file consumer type FILE_ROTATION
        $options->addOption('f', $options::FILE_PATH, true, MappIntelligenceMessages::$OPTION_FILE_PATH);
        $options->addOption('p', $options::FILE_PREFIX, true, MappIntelligenceMessages::$OPTION_FILE_PREFIX);

        $options->addOption('', $options::DEACTIVATE, false, MappIntelligenceMessages::$OPTION_DEACTIVATE);
        $options->addOption('', $options::HELP, false, MappIntelligenceMessages::$OPTION_HELP);
        $options->addOption('', $options::DEBUG, false, MappIntelligenceMessages::$OPTION_DEBUG);
        $options->addOption('', $options::VERSION, false, MappIntelligenceMessages::$OPTION_VERSION);

        return $options;
    }

    /**
     * @param array $config
     *
     * @return array
     * @throws MappIntelligenceCLIException
     */
    private function compareConfig($config)
    {
        $mappConfig = new MappIntelligenceConfig();
        $options = $this->getOptions($config);

        try {
            $options->parse();

            if ($options->hasOption($options::HELP)) {
                $options->printHelp();
            }

            if ($options->hasOption($options::VERSION)) {
                $options->printCLI('v' . MappIntelligenceVersion::get());
            }

            if ($options->hasOption($options::CONFIG)) {
                $mappConfig = new MappIntelligenceConfig($options->getOptionValue($options::CONFIG));
            }

            if ($options->hasOption($options::DEBUG)) {
                $mappConfig->setDebug(true);
            }

            if ($options->hasOption($options::DEACTIVATE)) {
                $mappConfig->setDeactivate(true);
            }

            if ($options->hasOption($options::TRACK_ID)) {
                $mappConfig->setTrackId($options->getOptionValue($options::TRACK_ID));
            }

            if ($options->hasOption($options::TRACK_DOMAIN)) {
                $mappConfig->setTrackDomain($options->getOptionValue($options::TRACK_DOMAIN));
            }

            if ($options->hasOption($options::CONSUMER_TYPE)) {
                $mappConfig->setConsumerType($options->getOptionValue($options::CONSUMER_TYPE));
            }

            if ($options->hasOption($options::FILENAME)) {
                $mappConfig->setFilename($options->getOptionValue($options::FILENAME));
            }

            if ($options->hasOption($options::FILE_PATH)) {
                $mappConfig->setFilePath($options->getOptionValue($options::FILE_PATH));
            }

            if ($options->hasOption($options::FILE_PREFIX)) {
                $mappConfig->setFilePrefix($options->getOptionValue($options::FILE_PREFIX));
            }
        } catch (MappIntelligenceCLIException $e) {
            $options->printHelp();
            throw new MappIntelligenceCLIException($e->getMessage());
        }

        return $mappConfig->build();
    }

    /**
     * @throws MappIntelligenceCLIException
     */
    private function validateOptions()
    {
        if ($this->isOptionInvalid($this->cfg[MappIntelligenceCLIOptions::TRACK_ID])) {
            throw new MappIntelligenceCLIException(MappIntelligenceMessages::$REQUIRED_TRACK_ID);
        }

        if ($this->isOptionInvalid($this->cfg[MappIntelligenceCLIOptions::TRACK_DOMAIN])) {
            throw new MappIntelligenceCLIException(MappIntelligenceMessages::$REQUIRED_TRACK_DOMAIN);
        }

        $ct = $this->cfg[MappIntelligenceCLIOptions::CONSUMER_TYPE];
        if ($ct !== MappIntelligenceConsumerType::FILE_ROTATION) {
            $this->cfg[MappIntelligenceCLIOptions::CONSUMER_TYPE] = MappIntelligenceConsumerType::FILE;
        }
    }

    /**
     * @return int exit status
     */
    public function run()
    {
        if ($this->deactivate) {
            $this->logger->log(MappIntelligenceMessages::$TRACKING_IS_DEACTIVATED);
            return self::EXIT_STATUS_SUCCESS;
        }

        if ($this->consumerType === MappIntelligenceConsumerType::FILE) {
            $fileTransmitter = new MappIntelligenceCLIFileTransmitter($this->cfg);
        } else {
            $fileTransmitter = new MappIntelligenceCLIFileRotationTransmitter($this->cfg);
        }

        return $fileTransmitter->send();
    }
}
