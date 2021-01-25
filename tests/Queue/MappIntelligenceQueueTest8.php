<?php

require_once __DIR__ . '/MappIntelligenceQueueTestCase.php';

/**
 * Class MappIntelligenceQueueTest
 */
class MappIntelligenceQueueTest extends MappIntelligenceQueueTestCase
{
    /**
     * after
     */
    public function tearDown(): void
    {
        parent::tearDown();

        $_COOKIE = array();
        unset($_SERVER['HTTP_USER_AGENT']);
        unset($_SERVER['REMOTE_ADDR']);
        unset($_SERVER['HTTP_HOST']);
        unset($_SERVER['HTTP_REFERER']);
        unset($_SERVER['REQUEST_URI']);
    }
}
