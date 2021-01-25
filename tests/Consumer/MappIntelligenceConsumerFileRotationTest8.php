<?php

require_once __DIR__ . '/MappIntelligenceConsumerFileRotationTestCase.php';

/**
 * Class MappIntelligenceConsumerFileTest
 */
class MappIntelligenceConsumerFileRotationTest extends MappIntelligenceConsumerFileRotationTestCase
{
    /**
     * after
     */
    public function tearDown(): void
    {
        MappIntelligenceUnitUtil::deleteFiles($this->tempFilePath, $this->tempFilePrefix, '.tmp');
        MappIntelligenceUnitUtil::deleteFiles($this->tempFilePath, $this->tempFilePrefix, '.log');
    }
}
