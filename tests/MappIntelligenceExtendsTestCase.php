<?php

/**
 * Class MappIntelligenceExtendsTestCase
 */
abstract class MappIntelligenceExtendsTestCase extends PHPUnit\Framework\TestCase
{
    /**
     * MappIntelligenceExtendsTestCase constructor.
     * @param null $name
     * @param array $data
     * @param string $dataName
     */
    public function __construct($name = null, array $data = array(), $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
    }

    /**
     * @param string $needle
     * @param string $haystack
     * @param string $message
     */
    public function assertContainsExtended($needle, $haystack, $message = '')
    {
        if (method_exists($this, 'assertStringContainsString')) {
            $this->assertStringContainsString($needle, $haystack, $message);
        } else {
            $this->assertContains($needle, $haystack, $message);
        }
    }

    /**
     * @param string $pattern
     * @param string $string
     * @param string $message
     */
    public function assertRegExpExtended($pattern, $string, $message = '')
    {
        if (method_exists($this, 'assertMatchesRegularExpression')) {
            $this->assertMatchesRegularExpression($pattern, $string, $message);
        } else {
            $this->assertRegExp($pattern, $string, $message);
        }
    }

    /**
     * @param string $pattern
     * @param string $string
     * @param string $message
     */
    public function assertNotRegExpExtended($pattern, $string, $message = '')
    {
        if (method_exists($this, 'assertDoesNotMatchRegularExpression')) {
            $this->assertDoesNotMatchRegularExpression($pattern, $string, $message);
        } else {
            $this->assertNotRegExp($pattern, $string, $message);
        }
    }

    /**
     * @param object|string $classOrObject
     * @param string $attributeName
     * @return mixed
     */
    public function readAttributeExtended($classOrObject, $attributeName)
    {
        if (method_exists($this, 'readAttribute')) {
            return $this->readAttribute($classOrObject, $attributeName);
        } else {
            return MappIntelligenceUnitUtil::getProperty($classOrObject, $attributeName);
        }
    }
}
