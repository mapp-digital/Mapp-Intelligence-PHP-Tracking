<?php

require_once __DIR__ . '/MappIntelligenceTestCase.php';

/**
 * Class MappIntelligenceTest
 */
class MappIntelligenceTest extends MappIntelligenceTestCase
{
    /**
     * before
     */
    public function setUp()
    {
        parent::setUp();

        MappIntelligenceUnitUtil::runkitFunctionRename('setcookie', 'origin_setcookie');
        MappIntelligenceUnitUtil::runkitFunctionAdd(
            'setcookie',
            '$name, $value = null, $expire = null, $path = null, $domain = null, $secure = null, $httponly = null',
            'MappIntelligenceTest::$SET_COOKIE_ARGS[] = func_get_args();'
        );
    }

    /**
     * after
     */
    public function tearDown()
    {
        parent::tearDown();

        self::$SET_COOKIE_ARGS = array();
        MappIntelligenceUnitUtil::runkitFunctionRemove('setcookie');
        MappIntelligenceUnitUtil::runkitFunctionRename('origin_setcookie', 'setcookie');
    }
}
