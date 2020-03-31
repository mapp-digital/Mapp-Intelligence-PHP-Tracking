<?php

/**
 * Class MappIntelligenceUnitUtil
 */
class MappIntelligenceUnitUtil extends PHPUnit\Framework\BaseTestListener
{
    private static $tmpErrorLog;
    private $errorLogConfig;

    /**
     * MappIntelligenceUnitUtil constructor.
     */
    public function __construct()
    {
        self::$tmpErrorLog = __DIR__ . '/tmp.txt';
    }

    /**
     *
     */
    private function clearWebtrekkInstance()
    {
        $mappIntelligence = MappIntelligence::getInstance();
        try {
            $reflection = new ReflectionClass($mappIntelligence);
            $instance = $reflection->getProperty('instance_');
            $instance->setAccessible(true);
            $instance->setValue(null, null);
            $instance->setAccessible(false);
        } catch (ReflectionException $e) {
            // do nothing
        }
    }

    /**
     *
     */
    private function activateErrorLog()
    {
        $this->errorLogConfig = ini_get('error_log');

        ini_set('error_log', self::$tmpErrorLog);
    }

    /**
     *
     */
    private function deactivateErrorLog()
    {
        ini_set('error_log', $this->errorLogConfig);

        if (file_exists(self::$tmpErrorLog)) {
            unlink(self::$tmpErrorLog);
        }
    }

    /**
     * @return string[]
     */
    public static function getErrorLog()
    {
        $fileContent = '';
        if (file_exists(self::$tmpErrorLog)) {
            $fileContent = file_get_contents(self::$tmpErrorLog);
        }

        return explode("\n", $fileContent);
    }

    /**
     * @param MappIntelligence|MappIntelligenceQueue $queue
     * @return MappIntelligenceQueue|string[]
     */
    public static function getQueue($queue)
    {
        $requests = array();

        try {
            $reflection = new ReflectionClass($queue);
            $instance = $reflection->getProperty('queue_');
            $instance->setAccessible(true);
            $requests = $instance->getValue($queue);
            $instance->setAccessible(false);
        } catch (ReflectionException $e) {
            // do nothing
        }

        return $requests;
    }

    /**
     * @param mixed $class
     * @param string $propertyName
     * @return mixed|null
     */
    public static function getProperty($class, $propertyName)
    {
        $property = null;

        try {
            $reflection = new ReflectionClass($class);
            $instance = $reflection->getProperty($propertyName);
            $instance->setAccessible(true);
            $property = $instance->getValue($class);
            $instance->setAccessible(false);
        } catch (ReflectionException $e) {
            // do nothing
        }

        return $property;
    }

    /**
     * @param PHPUnit\Framework\Test $test
     */
    public function startTest(PHPUnit\Framework\Test $test)
    {
        $this->activateErrorLog();

        mkdir('./tmp');
    }

    /**
     * @param PHPUnit\Framework\Test $test
     * @param float $time
     */
    public function endTest(PHPUnit\Framework\Test $test, $time)
    {
        // clear mapp intelligence instance after each test (singleton)
        $this->clearWebtrekkInstance();

        // clear error log file
        $this->deactivateErrorLog();

        $path = './tmp';
        if (is_dir($path)) {
            if (substr(php_uname(), 0, 7) === 'Windows') {
                exec(sprintf('rd /s /q %s', escapeshellarg($path)));
            } else {
                exec(sprintf('rm -rf %s', escapeshellarg($path)));
            }
        }
    }
}
