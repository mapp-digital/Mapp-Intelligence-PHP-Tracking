<?php

/**
 * Interface MappIntelligenceConsumer
 */
interface MappIntelligenceConsumer
{
    /**
     * @param array $batchContent List of tracking requests
     *
     * @return bool
     */
    public function sendBatch(array $batchContent);
}
