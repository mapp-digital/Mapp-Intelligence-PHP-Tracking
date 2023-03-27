<?php

require_once __DIR__ . '/MappIntelligenceLogger.php';
require_once __DIR__ . '/MappIntelligenceLogLevel.php';
require_once __DIR__ . '/MappIntelligenceMessages.php';

class MappIntelligenceDebugLogger implements MappIntelligenceLogger
{
    const DATE_FORMAT = 'd-m-Y H:i:s.v'; // e.g. 24-03-2023 13:43:27.000
    const MESSAGE_FORMAT = '%s %s [%s]: ';

    /**
     * Mapp Intelligence logger.
     */
    private $logger;
    /**
     * Defined the debug log level.
     */
    private $logLevel;

    /**
     * @param MappIntelligenceLogger $l Mapp Intelligence logger.
     * @param int $ll Debug log level.
     */
    public function __construct($l = null, $ll = 0)
    {
        $this->logger = $l;
        $this->logLevel = $ll;
    }

    /**
     * @param string $ll Debug log level
     *
     * @return string Message prefix
     */
    private function getMessagePrefix($ll)
    {
        return sprintf(
            MappIntelligenceDebugLogger::MESSAGE_FORMAT,
            date(MappIntelligenceDebugLogger::DATE_FORMAT),
            $ll,
            MappIntelligenceMessages::$MAPP_INTELLIGENCE
        );
    }

    private function logMessage($prefix, ...$msg)
    {
        if ($this->logger !== null) {
            $msg[0] = $prefix . $msg[0];
            $this->log(...$msg);
        }
    }

    /**
     * @param string ...$msg Debug message
     */
    public function log(...$msg)
    {
        if ($this->logger !== null) {
            $format = array_shift($msg);

            if (empty($msg)) {
                $this->logger->log($format);
            } else {
                $this->logger->log(sprintf($format, ...$msg));
            }
        }
    }

    /**
     * @param string ...$msg Debug message
     */
    public function fatal(...$msg)
    {
        if (MappIntelligenceLogLevel::FATAL <= $this->logLevel) {
            $this->logMessage($this->getMessagePrefix('FATAL'), ...$msg);
        }
    }

    /**
     * @param string ...$msg Debug message
     */
    public function error(...$msg)
    {
        if (MappIntelligenceLogLevel::ERROR <= $this->logLevel) {
            $this->logMessage($this->getMessagePrefix('ERROR'), ...$msg);
        }
    }

    /**
     * @param string ...$msg Debug message
     */
    public function warn(...$msg)
    {
        if (MappIntelligenceLogLevel::WARN <= $this->logLevel) {
            $this->logMessage($this->getMessagePrefix('WARN'), ...$msg);
        }
    }

    /**
     * @param string ...$msg Debug message
     */
    public function info(...$msg)
    {
        if (MappIntelligenceLogLevel::INFO <= $this->logLevel) {
            $this->logMessage($this->getMessagePrefix('INFO'), ...$msg);
        }
    }

    /**
     * @param string ...$msg Debug message
     */
    public function debug(...$msg)
    {
        if (MappIntelligenceLogLevel::DEBUG <= $this->logLevel) {
            $this->logMessage($this->getMessagePrefix('DEBUG'), ...$msg);
        }
    }
}
