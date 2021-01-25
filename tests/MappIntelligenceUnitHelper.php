<?php

$version = explode('.', phpversion());

if ($version[0] > 5) {
    class DynamicExtender implements PHPUnit\Framework\TestListener
    {
        use PHPUnit\Framework\TestListenerDefaultImplementation;
    }
} else {
    class DynamicExtender extends PHPUnit\Framework\BaseTestListener
    {

    }
}

/**
 * Class MappIntelligenceUnitHelper
 */
class MappIntelligenceUnitHelper extends DynamicExtender
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
    protected function clearWebtrekkInstance()
    {
        $mappIntelligence = MappIntelligence::getInstance();
        try {
            $reflection = new ReflectionClass($mappIntelligence);
            $instance = $reflection->getProperty('instance');
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
    protected function activateErrorLog()
    {
        $this->errorLogConfig = ini_get('error_log');

        ini_set('error_log', self::$tmpErrorLog);
    }

    /**
     *
     */
    protected function deactivateErrorLog()
    {
        ini_set('error_log', $this->errorLogConfig);

        if (file_exists(self::$tmpErrorLog)) {
            unlink(self::$tmpErrorLog);
        }
    }

    /**
     *
     */
    protected function startTestCase()
    {
        $this->activateErrorLog();

        $path = MAIN_DIRECTORY . 'tmp';
        mkdir($path);
    }

    /**
     *
     */
    protected function endTestCase()
    {
        // clear mapp intelligence instance after each test (singleton)
        $this->clearWebtrekkInstance();

        // clear error log file
        $this->deactivateErrorLog();

        $path = MAIN_DIRECTORY . 'tmp';
        if (is_dir($path)) {
            if (substr(php_uname(), 0, 7) === 'Windows') {
                exec(sprintf('rd /s /q %s', escapeshellarg($path)));
            } else {
                exec(sprintf('rm -rf %s', escapeshellarg($path)));
            }
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
            $instance = $reflection->getProperty('queue');
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
     * @param mixed $class
     * @param string $propertyName
     * @param mixed $value
     */
    public static function setProperty($class, $propertyName, $value)
    {
        try {
            $reflection = new ReflectionClass($class);
            $instance = $reflection->getProperty($propertyName);
            $instance->setAccessible(true);
            $instance->setValue($class, $value);
            $instance->setAccessible(false);
        } catch (ReflectionException $e) {
            // do nothing
        }
    }

    /**
     * @param string $oldName
     * @param string $newName
     */
    public static function runkitFunctionRename($oldName, $newName)
    {
        if (function_exists('runkit7_function_rename')) {
            runkit7_function_rename($oldName, $newName);
        } elseif (function_exists('runkit_function_rename')) {
            runkit_function_rename($oldName, $newName);
        }
    }

    /**
     * @param string $funcname
     * @param string $arglist
     * @param string $code
     */
    public static function runkitFunctionAdd($funcname, $arglist, $code)
    {
        if (function_exists('runkit7_function_add')) {
            runkit7_function_add($funcname, $arglist, $code);
        } elseif (function_exists('runkit_function_add')) {
            runkit_function_add($funcname, $arglist, $code);
        }
    }

    /**
     * @param string $funcname
     */
    public static function runkitFunctionRemove($funcname)
    {
        if (function_exists('runkit7_function_remove')) {
            runkit7_function_remove($funcname);
        } elseif (function_exists('runkit_function_remove')) {
            runkit_function_remove($funcname);
        }
    }

    /**
     * @param string $path Path to file
     * @param string $prefix Path to file
     * @param string $extension Path to file
     */
    public static function deleteFiles($path, $prefix, $extension)
    {
        $f = scandir($path);
        $files = array_filter($f, function ($name) use ($prefix, $extension) {
            return substr_compare($name, $prefix, 0, strlen($prefix)) === 0
                && substr_compare($name, $extension, -strlen($extension)) === 0;
        });

        if (!empty($files)) {
            foreach ($files as $file) {
                unlink($path . $file);
            }
        }
    }

    /**
     * @param string $path Path to file
     * @param string $prefix Path to file
     * @param string $extension Path to file
     *
     * @return array
     */
    public static function getFiles($path, $prefix, $extension)
    {
        $f = scandir($path);
        return array_filter($f, function ($name) use ($prefix, $extension) {
            return substr_compare($name, $prefix, 0, strlen($prefix)) === 0
                && substr_compare($name, $extension, -strlen($extension)) === 0;
        });
    }

    /**
     * @param string $filePath Path to file
     *
     * @return resource
     */
    public static function createFile($filePath)
    {
        return fopen($filePath, 'a+');
    }

    /**
     * @param string $path Path to file
     * @param string $prefix Path to file
     * @param string $extension Path to file
     *
     * @return String
     */
    public static function getFileContent($path, $prefix, $extension)
    {
        $f = scandir($path);
        $files = array_filter($f, function ($name) use ($prefix, $extension) {
            return substr_compare($name, $prefix, 0, strlen($prefix)) === 0
                && substr_compare($name, $extension, -strlen($extension)) === 0;
        });

        if (!empty($files)) {
            return file_get_contents($path . reset($files));
        }

        return '';
    }
}
