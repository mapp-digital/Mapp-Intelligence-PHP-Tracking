<?php

require_once __DIR__ . '/../MappIntelligenceLogger.php';

class MappIntelligenceDefaultLogger implements MappIntelligenceLogger
{
    /**
     * @param mixed ...$msg Debug message
     */
    public function log(...$msg)
    {
        error_log(...$msg);
    }
}
