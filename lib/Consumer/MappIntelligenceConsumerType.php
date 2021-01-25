<?php

/**
 * Class MappIntelligenceConsumerType
 */
class MappIntelligenceConsumerType
{
    /**
     * Identifier for file consumer.
     */
    const FILE = 'FILE';
    /**
     * Identifier for file rotation consumer.
     */
    const FILE_ROTATION = 'FILE_ROTATION';
    /**
     * Identifier for http client consumer.
     */
    const CURL = 'CURL';
    /**
     * Identifier for fork http client consumer.
     */
    const FORK_CURL = 'FORK_CURL';
    /**
     * Identifier for custom consumer.
     */
    const CUSTOM = 'CUSTOM';
}
