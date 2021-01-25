<?php

require_once __DIR__ . '/MappIntelligenceUnitHelper.php';

/**
 * Class MappIntelligenceUnitUtil
 */
class MappIntelligenceUnitUtil extends MappIntelligenceUnitHelper
{
    /**
     * @param PHPUnit_Framework_Test $test
     */
    public function startTest(PHPUnit_Framework_Test $test)
    {
        $this->startTestCase();
    }

    /**
     * @param PHPUnit_Framework_Test $test
     * @param float $time
     */
    public function endTest(PHPUnit_Framework_Test $test, $time)
    {
        $this->endTestCase();
    }
}
