<?php

require_once __DIR__ . '/MappIntelligenceConsumerFileTestCase.php';

/**
 * Class MappIntelligenceConsumerFileTest
 */
class MappIntelligenceConsumerFileTest extends MappIntelligenceConsumerFileTestCase
{
    public function __construct($name = null, array $data = array(), $dataName = '')
    {
        parent::__construct(__DIR__ . '/../../tmp/mapp_intelligence_test.log', $name, $data, $dataName);
    }

    /**
     * after
     */
    public function tearDown()
    {
        if (file_exists($this->tempFilename)) {
            unlink($this->tempFilename);
        }
    }
}
