<?php

require_once __DIR__ . '/MappIntelligenceUnitHelper.php';

/**
 * Class MappIntelligenceUnitUtil
 */
class MappIntelligenceUnitUtil extends MappIntelligenceUnitHelper
{
    /**
     * @param PHPUnit\Framework\Test $test
     */
    public function startTest(PHPUnit\Framework\Test $test)
    {
        $this->startTestCase();
    }

    /**
     * @param PHPUnit\Framework\Test $test
     * @param float $time
     */
    public function endTest(PHPUnit\Framework\Test $test, $time)
    {
        $this->endTestCase();
    }
}
