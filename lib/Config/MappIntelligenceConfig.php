<?php

require_once __DIR__ . '/MappIntelligenceProperties.php';
require_once __DIR__ . '/MappIntelligenceConfigProperties.php';
require_once __DIR__ . '/MappIntelligenceDefaultLogger.php';
require_once __DIR__ . '/../MappIntelligenceMessages.php';
require_once __DIR__ . '/../MappIntelligenceLogger.php';
require_once __DIR__ . '/../MappIntelligenceLogLevel.php';
require_once __DIR__ . '/../MappIntelligenceDebugLogger.php';
require_once __DIR__ . '/../Consumer/MappIntelligenceConsumerType.php';
require_once __DIR__ . '/../Queue/MappIntelligenceEnrichment.php';

/**
 * Class MappIntelligenceConfig
 */
class MappIntelligenceConfig
{
    /**
     * Constant for port 80.
     */
    const PORT_80 = 80;
    /**
     * Constant for port 443.
     */
    const PORT_443 = 443;
    /**
     * Constant for the default value of max attempt timeout.
     */
    const DEFAULT_ATTEMPT_TIMEOUT = 100;
    /**
     * Constant for the default value of max batch size.
     */
    const DEFAULT_MAX_BATCH_SIZE = 50;
    /**
     * Constant for the default value of max queue size.
     */
    const DEFAULT_MAX_QUEUE_SIZE = 1000;
    /**
     * Constant for the default value of max lines per file.
     */
    const DEFAULT_MAX_FILE_LINES = 10000; // 10 * 1000
    /**
     * Constant for the default value of max file duration (30 min).
     */
    const DEFAULT_MAX_FILE_DURATION = 1800000; // 30 * 60 * 1000
    /**
     * Constant for the default value of max file size (24 MB).
     */
    const DEFAULT_MAX_FILE_SIZE = 25165824; // 24 * 1024 * 1024
    /**
     * Constant for max attempt.
     */
    const MAX_ATTEMPT = 5;
    /**
     * Constant for max attempt timeout.
     */
    const MAX_ATTEMPT_TIMEOUT = 500;
    /**
     * Constant for string encoding.
     */
    const ENCODING = 'UTF-8';

    /**
     * Your Mapp Intelligence track ID provided by Mapp.
     */
    private $trackId = '';
    /**
     * Your Mapp Intelligence tracking domain.
     */
    private $trackDomain = '';
    /**
     * The domains you do not want to identify as an external referrer (e.g. your subdomains).
     */
    private $domain = array();
    /**
     * Deactivate the tracking functionality.
     */
    private $deactivate = false;
    /**
     * Deactivate the tracking functionality.
     */
    private $deactivateByInAndExclude = false;
    /**
     * Activates the debug mode.
     * @var MappIntelligenceDebugLogger
     */
    private $logger;
    /**
     * Activates the debug mode.
     */
    private $debug = false;
    /**
     * Defined the debug log level.
     */
    private $logLevel = MappIntelligenceLogLevel::ERROR;
    /**
     * The consumer to use for data transfer to Intelligence.
     */
    private $consumer;
    /**
     * The consumer type to use for data transfer to Intelligence.
     */
    private $consumerType = MappIntelligenceConsumerType::CURL;
    /**
     * The path to your request logging file.
     */
    private $filePath = '';
    /**
     * The prefix name for your request logging file.
     */
    private $filePrefix = '';
    /**
     * The path to your request logging file.
     */
    private $filename = '';
    /**
     * The file mode.
     */
    private $fileMode = 'a';
    /**
     * The number of resend attempts.
     */
    private $maxAttempt = 1;
    /**
     * The interval of request resend in milliseconds.
     */
    private $attemptTimeout = self::DEFAULT_ATTEMPT_TIMEOUT;
    /**
     * The maximum request number per batch.
     */
    private $maxBatchSize = self::DEFAULT_MAX_BATCH_SIZE;
    /**
     * The maximum number of requests saved in the queue.
     */
    private $maxQueueSize = self::DEFAULT_MAX_QUEUE_SIZE;
    /**
     * The maximum number of maximal lines per file.
     */
    private $maxFileLines = self::DEFAULT_MAX_FILE_LINES;
    /**
     * The maximum number of maximal file duration.
     */
    private $maxFileDuration = self::DEFAULT_MAX_FILE_DURATION;
    /**
     * The maximum number of maximal file size.
     */
    private $maxFileSize = self::DEFAULT_MAX_FILE_SIZE;
    /**
     * Sends every request via SSL.
     */
    private $forceSSL = true;
    /**
     * Specific URL parameter(s) in the default page name.
     */
    private $useParamsForDefaultPageName = array();
    /**
     * HTTP user agent string.
     */
    private $userAgent = '';
    /**
     * HTTP header for Sec-CH-UA
     */
    private $clientHintUserAgent = '';
    /**
     * HTTP header for Sec-CH-UA-Full-Version-List
     */
    private $clientHintUserAgentFullVersionList = '';
    /**
     * HTTP header for Sec-CH-UA-Model
     */
    private $clientHintUserAgentModel = '';
    /**
     * HTTP header for Sec-CH-UA-Mobile
     */
    private $clientHintUserAgentMobile = '';
    /**
     * HTTP header for Sec-CH-UA-Platform
     */
    private $clientHintUserAgentPlatform = '';
    /**
     * HTTP header for Sec-CH-UA-Platform-Version
     */
    private $clientHintUserAgentPlatformVersion = '';
    /**
     * Remote address (ip) from the client.
     */
    private $remoteAddress = '';
    /**
     * HTTP referrer URL.
     */
    private $referrerURL = '';
    /**
     * HTTP request URL.
     */
    private $requestURL = array();
    /**
     * Map with cookies.
     */
    private $cookie = array();
    /**
     * If the string is contained in the request URL, the request is measured.
     */
    private $containsInclude = array();
    /**
     * If the string is contained in the request URL, the request isn't measured.
     */
    private $containsExclude = array();
    /**
     * If the regular expression matches the request URL, the request is measured.
     */
    private $matchesInclude = array();
    /**
     * If the regular expression matches the request URL, the request isn't measured.
     */
    private $matchesExclude = array();

    /**
     * MappIntelligenceConfig constructor.
     * @param mixed $config
     */
    public function __construct($config = array())
    {
        if ($config instanceof MappIntelligenceConfig) {
            $config = $config->build();
        }

        if (is_string($config) || (is_array($config) && array_key_exists('config', $config))) {
            $this->initWithConfigFile(is_string($config) ? $config : $config['config']);
        }

        if (is_array($config)) {
            foreach ($config as $prop => $propValue) {
                $methodName = 'set' . ucfirst($prop);
                if (method_exists($this, $methodName)) {
                    $this->{$methodName}($propValue);
                }
            }

            $this->initOldConfiguration($config);
        }
    }

    /**
     * @param string $configFile Path to the configuration file (*.properties or *.xml).
     */
    private function initWithConfigFile($configFile)
    {
        $prop = new MappIntelligenceConfigProperties($configFile);

        $this->setTrackId($prop->getStringProperty(
            MappIntelligenceProperties::TRACK_ID,
            $this->trackId
        ))
        ->setTrackDomain($prop->getStringProperty(
            MappIntelligenceProperties::TRACK_DOMAIN,
            $this->trackDomain
        ))
        ->setDeactivate($prop->getBooleanProperty(
            MappIntelligenceProperties::DEACTIVATE,
            false
        ))
        ->setDomain($prop->getListProperty(
            MappIntelligenceProperties::DOMAIN,
            $this->domain
        ))
        ->setLogLevel($prop->getStringProperty(
            MappIntelligenceProperties::LOG_LEVEL,
            $this->logLevel
        ))
        ->setUseParamsForDefaultPageName($prop->getListProperty(
            MappIntelligenceProperties::USE_PARAMS_FOR_DEFAULT_PAGE_NAME,
            $this->useParamsForDefaultPageName
        ))
        ->setConsumerType($prop->getConsumerTypeProperty(
            MappIntelligenceProperties::CONSUMER,
            $this->consumerType
        ))
        ->setConsumerType($prop->getConsumerTypeProperty(
            MappIntelligenceProperties::CONSUMER_TYPE,
            $this->consumerType
        ))
        ->setFilePath($prop->getStringProperty(
            MappIntelligenceProperties::FILE_PATH,
            $this->filePath
        ))
        ->setFilePrefix($prop->getStringProperty(
            MappIntelligenceProperties::FILE_PREFIX,
            $this->filePrefix
        ))
        ->setFilename($prop->getStringProperty(
            MappIntelligenceProperties::FILE_NAME,
            $this->filename
        ))
        ->setFileMode($prop->getStringProperty(
            MappIntelligenceProperties::FILE_MODE,
            $this->fileMode
        ))
        ->setDebug($prop->getBooleanProperty(
            MappIntelligenceProperties::DEBUG,
            $this->debug
        ))
        ->setMaxAttempt($prop->getIntegerProperty(
            MappIntelligenceProperties::MAX_ATTEMPT,
            $this->maxAttempt
        ))
        ->setAttemptTimeout($prop->getIntegerProperty(
            MappIntelligenceProperties::ATTEMPT_TIMEOUT,
            $this->attemptTimeout
        ))
        ->setMaxBatchSize($prop->getIntegerProperty(
            MappIntelligenceProperties::MAX_BATCH_SIZE,
            $this->maxBatchSize
        ))
        ->setMaxQueueSize($prop->getIntegerProperty(
            MappIntelligenceProperties::MAX_QUEUE_SIZE,
            $this->maxQueueSize
        ))
        ->setMaxFileLines($prop->getIntegerProperty(
            MappIntelligenceProperties::MAX_FILE_LINES,
            $this->maxFileLines
        ))
        ->setMaxFileDuration($prop->getIntegerProperty(
            MappIntelligenceProperties::MAX_FILE_DURATION,
            $this->maxFileDuration
        ))
        ->setMaxFileSize($prop->getIntegerProperty(
            MappIntelligenceProperties::MAX_FILE_SIZE,
            $this->maxFileSize
        ))
        ->setForceSSL($prop->getBooleanProperty(
            MappIntelligenceProperties::FORCE_SSL,
            true
        ))
        ->setContainsInclude($prop->getListProperty(
            MappIntelligenceProperties::CONTAINS_INCLUDE,
            $this->containsInclude
        ))
        ->setContainsExclude($prop->getListProperty(
            MappIntelligenceProperties::CONTAINS_EXCLUDE,
            $this->containsExclude
        ))
        ->setMatchesInclude($prop->getListProperty(
            MappIntelligenceProperties::MATCHES_INCLUDE,
            $this->matchesInclude
        ))
        ->setMatchesExclude($prop->getListProperty(
            MappIntelligenceProperties::MATCHES_EXCLUDE,
            $this->matchesExclude
        ));
    }

    /**
     * @param array $config
     */
    private function initOldConfiguration($config)
    {
        if (array_key_exists('consumer', $config) && is_string($config['consumer'])) {
            $this->setConsumerType($config['consumer']);
            $this->consumer = null;
        }
    }

    /**
     * @return string
     */
    private function getUserAgent()
    {
        return array_key_exists('HTTP_USER_AGENT', $_SERVER) ? $_SERVER['HTTP_USER_AGENT'] : '';
    }

    /**
     * @return string
     */
    private function getRemoteAddress()
    {
        return array_key_exists('REMOTE_ADDR', $_SERVER) ? $_SERVER['REMOTE_ADDR'] : '';
    }

    /**
     * @return string
     */
    private function getRequestURL()
    {
        $host = array_key_exists('HTTP_HOST', $_SERVER) ? $_SERVER['HTTP_HOST'] : '';
        $request = array_key_exists('REQUEST_URI', $_SERVER) ? $_SERVER['REQUEST_URI'] : '';

        return 'https://' . $host . $request;
    }

    /**
     * @return string
     */
    private function getReferrerURL()
    {
        return array_key_exists('HTTP_REFERER', $_SERVER) ? $_SERVER['HTTP_REFERER'] : '';
    }

    /**
     * @return array
     */
    private function getCookie()
    {
        return $_COOKIE;
    }

    /**
     * @return string
     */
    private function getOwnDomain()
    {
        if (empty($this->requestURL)) {
            return '';
        }

        $serverPort = (isset($this->requestURL['port']) ? $this->requestURL['port'] : '');
        if ($serverPort === self::PORT_80 || $serverPort === self::PORT_443 || empty($serverPort)) {
            return $this->requestURL['host'];
        }

        return $this->requestURL['host'] . ':' . $serverPort;
    }

    /**
     * @return int
     */
    private function getStatistics()
    {
        $statistics = 0;

        if (count($this->useParamsForDefaultPageName) > 0) {
            $statistics += 1;
        }

        if ($this->forceSSL) {
            $statistics += 2;
        }

        if ($this->logger) {
            $statistics += 4;
        }

        if ($this->consumerType === MappIntelligenceConsumerType::CURL) {
            $statistics += 8;
        }

        if ($this->consumerType === MappIntelligenceConsumerType::FORK_CURL) {
            $statistics += 16;
        }

        if ($this->consumerType === MappIntelligenceConsumerType::FILE) {
            $statistics += 64;
        }

        if ($this->consumerType === MappIntelligenceConsumerType::FILE_ROTATION) {
            $statistics += 128;
        }

        if ($this->consumerType === MappIntelligenceConsumerType::CUSTOM) {
            $statistics += 256;
        }

        return $statistics;
    }

    /**
     * @param array $list List of strings, if is contained in the request URL, the request is/isn't measured
     *
     * @return boolean
     */
    private function checkContains($list)
    {
        $requestURL = MappIntelligenceEnrichment::unParseURI($this->requestURL);
        for ($i = 0; $i < count($list); $i++) {
            $s = $list[$i];
            if (strpos($requestURL, $s) !== false) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param array $list List of regular expressions, if it matches the request URL, the request is/isn't measured
     *
     * @return boolean
     */
    private function checkMatches($list)
    {
        $requestURL = MappIntelligenceEnrichment::unParseURI($this->requestURL);
        for ($i = 0; $i < count($list); $i++) {
            $s = $list[$i];
            try {
                if (preg_match($s, $requestURL)) {
                    return true;
                }
            } catch (Exception $e) {
                $this->logger->error(MappIntelligenceMessages::$GENERIC_ERROR, $e->getFile(), $e->getMessage());
            }
        }

        return false;
    }

    /**
     * @return boolean
     */
    private function isDeactivateByInAndExclude()
    {
        if (empty($this->requestURL)) {
            return false;
        }

        $isContainsIncludeEmpty = count($this->containsInclude) === 0;
        $isMatchesIncludeEmpty = count($this->matchesInclude) === 0;
        $isContainsExcludeEmpty = count($this->containsExclude) === 0;
        $isMatchesExcludeEmpty = count($this->matchesExclude) === 0;

        $isIncluded = $isContainsIncludeEmpty && $isMatchesIncludeEmpty;

        if (!$isContainsIncludeEmpty) {
            $isIncluded = $this->checkContains($this->containsInclude);
        }

        if (!$isIncluded && !$isMatchesIncludeEmpty) {
            $isIncluded = $this->checkMatches($this->matchesInclude);
        }

        if ($isIncluded && !$isContainsExcludeEmpty) {
            $isIncluded = !$this->checkContains($this->containsExclude);
        }

        if ($isIncluded && !$isMatchesExcludeEmpty) {
            $isIncluded = !$this->checkMatches($this->matchesExclude);
        }

        return !$isIncluded;
    }

    /**
     * @param mixed $value Origin value
     * @param mixed $def Default value
     *
     * @return mixed
     */
    private function getOrDefault($value, $def)
    {
        return (!empty($value)) ? $value : $def;
    }

    /**
     * @param string $tId Enter your Mapp Intelligence track ID provided by Mapp
     *
     * @return $this
     */
    public function setTrackId($tId)
    {
        $this->trackId = $this->getOrDefault($tId, $this->trackId);
        return $this;
    }

    /**
     * @param string $tDomain Enter your Mapp Intelligence tracking URL
     *
     * @return $this
     */
    public function setTrackDomain($tDomain)
    {
        $this->trackDomain = $this->getOrDefault($tDomain, $this->trackDomain);
        return $this;
    }

    /**
     * @param string $ua HTTP user agent string
     *
     * @return $this
     */
    public function setUserAgent($ua)
    {
        if ($ua) {
            $this->userAgent = $this->getOrDefault(rawurldecode($ua), $this->userAgent);
        }

        return $this;
    }

    /**
     * @param string $ch HTTP Header for Sec-CH-UA
     *
     * @return $this
     */
    public function setClientHintUserAgent($ch)
    {
        if ($ch) {
            $this->clientHintUserAgent = $this->getOrDefault(rawurldecode($ch), $this->clientHintUserAgent);
        }

        return $this;
    }

    /**
     * @param string $ch HTTP Header for Sec-CH-UA-Full-Version-List
     *
     * @return $this
     */
    public function setClientHintUserAgentFullVersionList($ch)
    {
        if ($ch) {
            $this->clientHintUserAgentFullVersionList = $this->getOrDefault(
                rawurldecode($ch),
                $this->clientHintUserAgentFullVersionList
            );
        }

        return $this;
    }

    /**
     * @param string $ch HTTP Header for Sec-CH-UA-Model
     *
     * @return $this
     */
    public function setClientHintUserAgentModel($ch)
    {
        if ($ch) {
            $this->clientHintUserAgentModel = $this->getOrDefault(rawurldecode($ch), $this->clientHintUserAgentModel);
        }

        return $this;
    }

    /**
     * @param string $ch HTTP Header for Sec-CH-UA-Mobile
     *
     * @return $this
     */
    public function setClientHintUserAgentMobile($ch)
    {
        if ($ch) {
            $this->clientHintUserAgentMobile = $this->getOrDefault(rawurldecode($ch), $this->clientHintUserAgentMobile);
        }

        return $this;
    }

    /**
     * @param string $ch HTTP Header for Sec-CH-UA-Platform
     *
     * @return $this
     */
    public function setClientHintUserAgentPlatform($ch)
    {
        if ($ch) {
            $this->clientHintUserAgentPlatform = $this->getOrDefault(
                rawurldecode($ch),
                $this->clientHintUserAgentPlatform
            );
        }

        return $this;
    }

    /**
     * @param string $ch HTTP Header for Sec-CH-UA-Platform-Version
     *
     * @return $this
     */
    public function setClientHintUserAgentPlatformVersion($ch)
    {
        if ($ch) {
            $this->clientHintUserAgentPlatformVersion = $this->getOrDefault(
                rawurldecode($ch),
                $this->clientHintUserAgentPlatformVersion
            );
        }

        return $this;
    }

    /**
     * @param string $ra Remote address (ip) from the client
     *
     * @return $this
     */
    public function setRemoteAddress($ra)
    {
        if ($ra) {
            $this->remoteAddress = $this->getOrDefault(rawurldecode($ra), $this->remoteAddress);
        }

        return $this;
    }

    /**
     * @param string $refURL HTTP referrer URL
     *
     * @return $this
     */
    public function setReferrerURL($refURL)
    {
        $this->referrerURL = $this->getOrDefault($refURL, $this->referrerURL);
        return $this;
    }

    /**
     * @param string $rURL HTTP request URL
     *
     * @return $this
     */
    public function setRequestURL($rURL)
    {
        if (filter_var($rURL, FILTER_VALIDATE_URL)) {
            $this->requestURL = parse_url($rURL);
        }

        return $this;
    }

    /**
     * @param array $cookies Map with cookies
     *
     * @return $this
     */
    public function setCookie($cookies)
    {
        $data = $this->getOrDefault($cookies, array());
        foreach ($data as $key => $value) {
            $this->addCookie($key, $value);
        }
        return $this;
    }

    /**
     * @param string $name Name of the cookie
     * @param string $value Value of the cookie
     *
     * @return $this
     */
    public function addCookie($name, $value)
    {
        if (!is_null($name) && !is_null($value)) {
            $this->cookie[rawurldecode($name)] = rawurldecode($value);
        }

        return $this;
    }

    /**
     * @param array $d Specify the domains you do not want to identify as an external referrer (e.g. your subdomains)
     *
     * @return $this
     */
    public function setDomain($d)
    {
        $this->domain = $this->getOrDefault($d, $this->domain);
        return $this;
    }

    /**
     * @param string $d Specify the domains you do not want to identify as an external referrer (e.g. your subdomains)
     *
     * @return $this
     */
    public function addDomain($d)
    {
        if (!empty($d)) {
            $this->domain[] = $d;
        }
        return $this;
    }

    /**
     * @param MappIntelligenceLogger $l Activates the debug mode.
     *                                  The debug mode sends messages to the custom logger class
     *
     * @return $this
     */
    public function setLogger($l)
    {
        if ($l instanceof MappIntelligenceLogger) {
            $this->logger = new MappIntelligenceDebugLogger($l, $this->logLevel);
        }

        return $this;
    }

    /**
     * @param bool $d Activates the debug mode. The debug mode sends messages to the custom logger class
     *
     * @return $this
     */
    public function setDebug($d)
    {
        if ($d) {
            $this->setLogger(new MappIntelligenceDefaultLogger());
        }

        return $this;
    }

    /**
     * @param mixed $ll Specify the debug log level
     *
     * @return $this
     */
    public function setLogLevel($ll)
    {
        $type = gettype($ll);
        if ($type === 'integer') {
            if ($ll >= MappIntelligenceLogLevel::NONE && $ll <= MappIntelligenceLogLevel::DEBUG) {
                $this->logLevel = $ll;
            }
        } elseif ($type === 'string') {
            return $this->setLogLevel(MappIntelligenceLogLevel::getValue($ll));
        }

        return $this->setLogger($this->logger);
    }

    /**
     * @param bool $d Deactivate the tracking functionality
     *
     * @return $this
     */
    public function setDeactivate($d)
    {
        $this->deactivate = $d;
        return $this;
    }

    /**
     * @param string $cType Specify the consumer to use for data transfer to Intelligence
     *
     * @return $this
     */
    public function setConsumerType($cType)
    {
        $this->consumerType = mb_strtoupper($this->getOrDefault($cType, $this->consumerType), self::ENCODING);
        return $this;
    }

    /**
     * @param MappIntelligenceConsumer $c Specify the consumer to use for data transfer to Intelligence
     *
     * @return $this
     */
    public function setConsumer($c)
    {
        $this->consumer = $this->getOrDefault($c, $this->consumer);
        return $this;
    }

    /**
     * @param string $f Enter the path to your request logging file. This is only relevant when using file consumer
     *
     * @return $this
     */
    public function setFilename($f)
    {
        $this->filename = $this->getOrDefault($f, $this->filename);
        return $this;
    }

    /**
     * @param string $fm Enter the file mode
     *
     * @return $this
     */
    public function setFileMode($fm)
    {
        if ($fm === 'a' || $fm === 'c') {
            $this->fileMode = $fm;
        }
        return $this;
    }

    /**
     * @param string $f Enter the path to your request logging file.
     *                  This is only relevant when using file rotation consumer
     *
     * @return $this
     */
    public function setFilePath($f)
    {
        $this->filePath = $this->getOrDefault($f, $this->filePath);
        return $this;
    }

    /**
     * @param string $f Enter the file prefix for your request logging file.
     *                  This is only relevant when using file rotation consumer
     *
     * @return $this
     */
    public function setFilePrefix($f)
    {
        $this->filePrefix = $this->getOrDefault($f, $this->filePrefix);
        return $this;
    }

    /**
     * @param int $mAttempt Specify the number of resend attempts. After the maxAttempts have been reached, the requests
     *                      will be deleted even if the sending failed
     *
     * @return $this
     */
    public function setMaxAttempt($mAttempt)
    {
        if ($mAttempt >= 1 && $mAttempt <= self::MAX_ATTEMPT) {
            $this->maxAttempt = $mAttempt;
        }

        return $this;
    }

    /**
     * @param int $aTimeout Specify the interval of request resend in milliseconds
     *
     * @return $this
     */
    public function setAttemptTimeout($aTimeout)
    {
        if ($aTimeout >= 1 && $aTimeout <= self::MAX_ATTEMPT_TIMEOUT) {
            $this->attemptTimeout = $aTimeout;
        }

        return $this;
    }

    /**
     * @param int $mBatchSize Specify the maximum request number per batch
     *
     * @return $this
     */
    public function setMaxBatchSize($mBatchSize)
    {
        $this->maxBatchSize = $mBatchSize;
        return $this;
    }

    /**
     * @param int $mQueueSize Specify the maximum number of requests saved in the queue
     *
     * @return $this
     */
    public function setMaxQueueSize($mQueueSize)
    {
        $this->maxQueueSize = $mQueueSize;
        return $this;
    }

    /**
     * @param int $mFileLines Specify the number of maximal lines per file.
     *
     * @return $this
     */
    public function setMaxFileLines($mFileLines)
    {
        if ($mFileLines >= 1 && $mFileLines <= self::DEFAULT_MAX_FILE_LINES) {
            $this->maxFileLines = $mFileLines;
        }

        return $this;
    }

    /**
     * @param int $mFileDuration Specify the number of maximal file duration.
     *
     * @return $this
     */
    public function setMaxFileDuration($mFileDuration)
    {
        if ($mFileDuration >= 1 && $mFileDuration <= self::DEFAULT_MAX_FILE_DURATION) {
            $this->maxFileDuration = $mFileDuration;
        }

        return $this;
    }

    /**
     * @param int $mFileSize Specify the number of maximal file size.
     *
     * @return $this
     */
    public function setMaxFileSize($mFileSize)
    {
        if ($mFileSize >= 1 && $mFileSize <= self::DEFAULT_MAX_FILE_SIZE) {
            $this->maxFileSize = $mFileSize;
        }

        return $this;
    }

    /**
     * @param bool $fSSL Sends every request via SSL
     *
     * @return $this
     */
    public function setForceSSL($fSSL)
    {
        $this->forceSSL = $fSSL;
        return $this;
    }

    /**
     * @param array $uParamsForDefaultPageName Includes specific URL parameter(s) in the default page name
     *
     * @return $this
     */
    public function setUseParamsForDefaultPageName($uParamsForDefaultPageName)
    {
        $this->useParamsForDefaultPageName = $this->getOrDefault(
            $uParamsForDefaultPageName,
            $this->useParamsForDefaultPageName
        );
        return $this;
    }

    /**
     * @param string $uParamsForDefaultPageName Includes specific URL parameter(s) in the default page name
     *
     * @return $this
     */
    public function addUseParamsForDefaultPageName($uParamsForDefaultPageName)
    {
        if (!is_null($uParamsForDefaultPageName)) {
            $this->useParamsForDefaultPageName[] = $uParamsForDefaultPageName;
        }
        return $this;
    }

    /**
     * @param array $cInclude Specify the strings that must be contained in the request URL to measure the request
     *
     * @return $this
     */
    public function setContainsInclude($cInclude)
    {
        $this->containsInclude = $this->getOrDefault($cInclude, $this->containsInclude);
        return $this;
    }

    /**
     * @param string $cInclude Specify the string that must be contained in the request URL to measure the request
     *
     * @return $this
     */
    public function addContainsInclude($cInclude)
    {
        if (!is_null($cInclude)) {
            $this->containsInclude[] = $cInclude;
        }
        return $this;
    }

    /**
     * @param array $cExclude Specify the strings that must be contained in the request URL to not measure the request
     *
     * @return $this
     */
    public function setContainsExclude($cExclude)
    {
        $this->containsExclude = $this->getOrDefault($cExclude, $this->containsExclude);
        return $this;
    }

    /**
     * @param string $cExclude Specify the string that must be contained in the request URL to not measure the request
     *
     * @return $this
     */
    public function addContainsExclude($cExclude)
    {
        if (!is_null($cExclude)) {
            $this->containsExclude[] = $cExclude;
        }
        return $this;
    }


    /**
     * @param array $mInclude Specify the regular expressions that must be match the request URL to measure the request
     *
     * @return $this
     */
    public function setMatchesInclude($mInclude)
    {
        $this->matchesInclude = $this->getOrDefault($mInclude, $this->matchesInclude);
        return $this;
    }

    /**
     * @param string $mInclude Specify the regular expression that must be match the request URL to measure the request
     *
     * @return $this
     */
    public function addMatchesInclude($mInclude)
    {
        if (!is_null($mInclude)) {
            $this->matchesInclude[] = $mInclude;
        }
        return $this;
    }

    /**
     * @param array $mExclude Specify the regular expressions that must be match the request URL to not
     *                        measure the request
     *
     * @return $this
     */
    public function setMatchesExclude($mExclude)
    {
        $this->matchesExclude = $this->getOrDefault($mExclude, $this->matchesExclude);
        return $this;
    }

    /**
     * @param string $mExclude Specify the regular expression that must be match the request URL to not
     *                         measure the request
     *
     * @return $this
     */
    public function addMatchesExclude($mExclude)
    {
        if (!is_null($mExclude)) {
            $this->matchesExclude[] = $mExclude;
        }
        return $this;
    }

    /**
     * @return array
     */
    public function build()
    {
        if (empty($this->referrerURL)) {
            $this->setReferrerURL($this->getReferrerURL());
        }

        if (empty($this->requestURL)) {
            $this->setRequestURL($this->getRequestURL());
        }

        if (empty($this->remoteAddress)) {
            $this->setRemoteAddress($this->getRemoteAddress());
        }

        if (empty($this->userAgent)) {
            $this->setUserAgent($this->getUserAgent());
        }

        if (empty($this->cookie)) {
            $this->setCookie($this->getCookie());
        }

        if (empty($this->domain)) {
            $this->addDomain($this->getOwnDomain());
        }

        if (empty($this->logger)) {
            $this->setLogger(new MappIntelligenceDebugLogger());
        }

        if (empty($this->filename)) {
            $this->filename = sys_get_temp_dir() . '/MappIntelligenceRequests.log';
        }

        if (empty($this->filePath)) {
            $this->filePath = sys_get_temp_dir() . '/';
        }

        if (empty($this->filePrefix)) {
            $this->filePrefix = 'MappIntelligenceRequests';
        }

        if ($this->consumerType === MappIntelligenceConsumerType::FILE
            || $this->consumerType === MappIntelligenceConsumerType::FILE_ROTATION) {
            $this->maxBatchSize = 1;
        }

        if (count($this->containsInclude) > 0 || count($this->containsExclude) > 0
            || count($this->matchesInclude) > 0 || count($this->matchesExclude) > 0) {
            $this->deactivateByInAndExclude = $this->isDeactivateByInAndExclude();
        }

        $statistics = $this->getStatistics();

        return array(
            'trackId' => $this->trackId,
            'trackDomain' => $this->trackDomain,
            'domain' => $this->domain,
            'deactivate' => $this->deactivate,
            'deactivateByInAndExclude' => $this->deactivateByInAndExclude,
            'logger' => $this->logger,
            'logLevel' => $this->logLevel,
            'consumer' => $this->consumer,
            'consumerType' => $this->consumerType,
            'filePath' => $this->filePath,
            'filePrefix' => $this->filePrefix,
            'filename' => $this->filename,
            'fileMode' => $this->fileMode,
            'maxAttempt' => $this->maxAttempt,
            'attemptTimeout' => $this->attemptTimeout,
            'maxBatchSize' => $this->maxBatchSize,
            'maxQueueSize' => $this->maxQueueSize,
            'maxFileLines' => $this->maxFileLines,
            'maxFileDuration' => $this->maxFileDuration,
            'maxFileSize' => $this->maxFileSize,
            'forceSSL' => $this->forceSSL,
            'useParamsForDefaultPageName' => $this->useParamsForDefaultPageName,
            'userAgent' => $this->userAgent,
            'clientHintUserAgent' => $this->clientHintUserAgent,
            'clientHintUserAgentFullVersionList' => $this->clientHintUserAgentFullVersionList,
            'clientHintUserAgentModel' => $this->clientHintUserAgentModel,
            'clientHintUserAgentMobile' => $this->clientHintUserAgentMobile,
            'clientHintUserAgentPlatform' => $this->clientHintUserAgentPlatform,
            'clientHintUserAgentPlatformVersion' => $this->clientHintUserAgentPlatformVersion,
            'remoteAddress' => $this->remoteAddress,
            'referrerURL' => $this->referrerURL,
            'requestURL' => $this->requestURL,
            'cookie' => $this->cookie,
            'containsInclude' => $this->containsInclude,
            'containsExclude' => $this->containsExclude,
            'matchesInclude' => $this->matchesInclude,
            'matchesExclude' => $this->matchesExclude,
            'statistics' => $statistics
        );
    }
}
