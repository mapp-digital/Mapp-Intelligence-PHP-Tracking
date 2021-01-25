<?php

require_once __DIR__ . '/MappIntelligenceLogger.php';
require_once __DIR__ . '/MappIntelligenceMessages.php';

class MappIntelligenceDebugLogger implements MappIntelligenceLogger
{
    /**
     * Mapp Intelligence logger.
     */
    private $logger;

    /**
     * @param MappIntelligenceLogger $l Mapp Intelligence logger.
     */
    public function __construct($l = null)
    {
        $this->logger = $l;
    }

    /**
     * @param string ...$msg Debug message
     */
    public function log(...$msg)
    {
        if ($this->logger !== null) {
            $format = MappIntelligenceMessages::$MAPP_INTELLIGENCE . array_shift($msg);

            if (empty($msg)) {
                $this->logger->log($format);
            } else {
                $this->logger->log(sprintf($format, ...$msg));
            }
        }
    }
}
