<?php

/**
 * Class MappIntelligenceParameter
 */
class MappIntelligenceLogLevel
{
    const NONE = 0;
    const FATAL = 1;
    const ERROR = 2;
    const WARN = 3;
    const INFO = 4;
    const DEBUG = 5;

    /**
     * @return array returns all constants of this class
     */
    public static function getConstants()
    {
        try {
            $reflectionClass = new ReflectionClass(__CLASS__);
            return $reflectionClass->getConstants();
            // @codeCoverageIgnoreStart
        } catch (ReflectionException $e) {
            return array();
            // @codeCoverageIgnoreEnd
        }
    }

    /**
     * @param int $ll Integer log level
     *
     * @return string Log level name
     */
    public static function getName($ll)
    {
        $declaredFields = MappIntelligenceLogLevel::getConstants();
        foreach ($declaredFields as $key => $value) {
            if ($value === $ll) {
                return $key;
            }
        }

        return null;
    }

    /**
     * @param string $ll Log level name
     *
     * @return int Integer log level
     */
    public static function getValue($ll)
    {
        $logLvl = mb_strtoupper($ll, 'UTF-8');

        $declaredFields = MappIntelligenceLogLevel::getConstants();
        foreach ($declaredFields as $key => $value) {
            if ($key === $logLvl) {
                return $value;
            }
        }

        return -1;
    }
}
