<?php

require_once __DIR__ . '/MappIntelligenceExtendsTestCase.php';

/**
 * Class MappIntelligenceDebugLoggerTest
 */
class MappIntelligenceDebugLoggerTest extends MappIntelligenceExtendsTestCase
{
    public function testNewMappIntelligenceDebugLogger()
    {
        try {
            new MappIntelligenceDebugLogger(new MappIntelligenceDefaultLogger(), MappIntelligenceLogLevel::DEBUG);
            $this->assertTrue(true);
        } catch (Exception $e) {
            $this->assertTrue(false);
        }
    }

    public function testNewMappIntelligenceDebugLogger2()
    {
        try {
            new MappIntelligenceDebugLogger(null, MappIntelligenceLogLevel::DEBUG);
            $this->assertTrue(true);
        } catch (Exception $e) {
            $this->assertTrue(false);
        }
    }

    public function testFatal1()
    {
        $logger = new MappIntelligenceDebugLogger(new MappIntelligenceDefaultLogger(), MappIntelligenceLogLevel::FATAL);

        $logger->fatal("fatal1");
        $logger->fatal("%s %s", "fatal2", "fatal3");

        $fileContent = join("\n", MappIntelligenceUnitUtil::getErrorLog());
        $this->assertContainsExtended("FATAL [Mapp Intelligence]: fatal1", $fileContent);
        $this->assertContainsExtended("FATAL [Mapp Intelligence]: fatal2 fatal3", $fileContent);
    }

    public function testFatal2()
    {
        $logger = new MappIntelligenceDebugLogger(null, MappIntelligenceLogLevel::FATAL);

        $logger->fatal("fatal1");
        $logger->fatal("%s %s", "fatal2", "fatal3");

        $fileContent = join("\n", MappIntelligenceUnitUtil::getErrorLog());
        $this->assertTrue(empty($fileContent));
    }

    public function testFatal3()
    {
        $logger = new MappIntelligenceDebugLogger(new MappIntelligenceDefaultLogger(), MappIntelligenceLogLevel::NONE);

        $logger->fatal("fatal1");
        $logger->fatal("%s %s", "fatal2", "fatal3");

        $fileContent = join("\n", MappIntelligenceUnitUtil::getErrorLog());
        $this->assertTrue(empty($fileContent));
    }

    public function testFatal4()
    {
        $logger = new MappIntelligenceDebugLogger(new MappIntelligenceDefaultLogger(), MappIntelligenceLogLevel::DEBUG);

        $logger->fatal("fatal1");
        $logger->fatal("%s %s", "fatal2", "fatal3");

        $fileContent = join("\n", MappIntelligenceUnitUtil::getErrorLog());
        $this->assertContainsExtended("FATAL [Mapp Intelligence]: fatal1", $fileContent);
        $this->assertContainsExtended("FATAL [Mapp Intelligence]: fatal2 fatal3", $fileContent);
    }

    public function testError1()
    {
        $logger = new MappIntelligenceDebugLogger(new MappIntelligenceDefaultLogger(), MappIntelligenceLogLevel::ERROR);

        $logger->error("error1");
        $logger->error("%s %s", "error2", "error3");

        $fileContent = join("\n", MappIntelligenceUnitUtil::getErrorLog());
        $this->assertContainsExtended("ERROR [Mapp Intelligence]: error1", $fileContent);
        $this->assertContainsExtended("ERROR [Mapp Intelligence]: error2 error3", $fileContent);
    }

    public function testError2()
    {
        $logger = new MappIntelligenceDebugLogger(null, MappIntelligenceLogLevel::ERROR);

        $logger->error("error1");
        $logger->error("%s %s", "error2", "error3");

        $fileContent = join("\n", MappIntelligenceUnitUtil::getErrorLog());
        $this->assertTrue(empty($fileContent));
    }

    public function testError3()
    {
        $logger = new MappIntelligenceDebugLogger(new MappIntelligenceDefaultLogger(), MappIntelligenceLogLevel::NONE);

        $logger->error("error1");
        $logger->error("%s %s", "error2", "error3");

        $fileContent = join("\n", MappIntelligenceUnitUtil::getErrorLog());
        $this->assertTrue(empty($fileContent));
    }

    public function testError4()
    {
        $logger = new MappIntelligenceDebugLogger(new MappIntelligenceDefaultLogger(), MappIntelligenceLogLevel::DEBUG);

        $logger->error("error1");
        $logger->error("%s %s", "error2", "error3");

        $fileContent = join("\n", MappIntelligenceUnitUtil::getErrorLog());
        $this->assertContainsExtended("ERROR [Mapp Intelligence]: error1", $fileContent);
        $this->assertContainsExtended("ERROR [Mapp Intelligence]: error2 error3", $fileContent);
    }

    public function testWarn1()
    {
        $logger = new MappIntelligenceDebugLogger(new MappIntelligenceDefaultLogger(), MappIntelligenceLogLevel::WARN);

        $logger->warn("warn1");
        $logger->warn("%s %s", "warn2", "warn3");

        $fileContent = join("\n", MappIntelligenceUnitUtil::getErrorLog());
        $this->assertContainsExtended("WARN [Mapp Intelligence]: warn1", $fileContent);
        $this->assertContainsExtended("WARN [Mapp Intelligence]: warn2 warn3", $fileContent);
    }

    public function testWarn2()
    {
        $logger = new MappIntelligenceDebugLogger(null, MappIntelligenceLogLevel::WARN);

        $logger->warn("warn1");
        $logger->warn("%s %s", "warn2", "warn3");

        $fileContent = join("\n", MappIntelligenceUnitUtil::getErrorLog());
        $this->assertTrue(empty($fileContent));
    }

    public function testWarn3()
    {
        $logger = new MappIntelligenceDebugLogger(new MappIntelligenceDefaultLogger(), MappIntelligenceLogLevel::NONE);

        $logger->warn("warn1");
        $logger->warn("%s %s", "warn2", "warn3");

        $fileContent = join("\n", MappIntelligenceUnitUtil::getErrorLog());
        $this->assertTrue(empty($fileContent));
    }

    public function testWarn4()
    {
        $logger = new MappIntelligenceDebugLogger(new MappIntelligenceDefaultLogger(), MappIntelligenceLogLevel::DEBUG);

        $logger->warn("warn1");
        $logger->warn("%s %s", "warn2", "warn3");

        $fileContent = join("\n", MappIntelligenceUnitUtil::getErrorLog());
        $this->assertContainsExtended("WARN [Mapp Intelligence]: warn1", $fileContent);
        $this->assertContainsExtended("WARN [Mapp Intelligence]: warn2 warn3", $fileContent);
    }

    public function testInfo1()
    {
        $logger = new MappIntelligenceDebugLogger(new MappIntelligenceDefaultLogger(), MappIntelligenceLogLevel::INFO);

        $logger->info("info1");
        $logger->info("%s %s", "info2", "info3");

        $fileContent = join("\n", MappIntelligenceUnitUtil::getErrorLog());
        $this->assertContainsExtended("INFO [Mapp Intelligence]: info1", $fileContent);
        $this->assertContainsExtended("INFO [Mapp Intelligence]: info2 info3", $fileContent);
    }

    public function testInfo2()
    {
        $logger = new MappIntelligenceDebugLogger(null, MappIntelligenceLogLevel::INFO);

        $logger->info("info1");
        $logger->info("%s %s", "info2", "info3");

        $fileContent = join("\n", MappIntelligenceUnitUtil::getErrorLog());
        $this->assertTrue(empty($fileContent));
    }

    public function testInfo3()
    {
        $logger = new MappIntelligenceDebugLogger(new MappIntelligenceDefaultLogger(), MappIntelligenceLogLevel::NONE);

        $logger->info("info1");
        $logger->info("%s %s", "info2", "info3");

        $fileContent = join("\n", MappIntelligenceUnitUtil::getErrorLog());
        $this->assertTrue(empty($fileContent));
    }

    public function testInfo4()
    {
        $logger = new MappIntelligenceDebugLogger(new MappIntelligenceDefaultLogger(), MappIntelligenceLogLevel::DEBUG);

        $logger->info("info1");
        $logger->info("%s %s", "info2", "info3");

        $fileContent = join("\n", MappIntelligenceUnitUtil::getErrorLog());
        $this->assertContainsExtended("INFO [Mapp Intelligence]: info1", $fileContent);
        $this->assertContainsExtended("INFO [Mapp Intelligence]: info2 info3", $fileContent);
    }

    public function testDebug1()
    {
        $logger = new MappIntelligenceDebugLogger(new MappIntelligenceDefaultLogger(), MappIntelligenceLogLevel::DEBUG);

        $logger->debug("debug1");
        $logger->debug("%s %s", "debug2", "debug3");

        $fileContent = join("\n", MappIntelligenceUnitUtil::getErrorLog());
        $this->assertContainsExtended("DEBUG [Mapp Intelligence]: debug1", $fileContent);
        $this->assertContainsExtended("DEBUG [Mapp Intelligence]: debug2 debug3", $fileContent);
    }

    public function testDebug2()
    {
        $logger = new MappIntelligenceDebugLogger(null, MappIntelligenceLogLevel::DEBUG);

        $logger->debug("debug1");
        $logger->debug("%s %s", "debug2", "debug3");

        $fileContent = join("\n", MappIntelligenceUnitUtil::getErrorLog());
        $this->assertTrue(empty($fileContent));
    }

    public function testDebug3()
    {
        $logger = new MappIntelligenceDebugLogger(new MappIntelligenceDefaultLogger(), MappIntelligenceLogLevel::NONE);

        $logger->debug("debug1");
        $logger->debug("%s %s", "debug2", "debug3");

        $fileContent = join("\n", MappIntelligenceUnitUtil::getErrorLog());
        $this->assertTrue(empty($fileContent));
    }

    public function testDebug4()
    {
        $logger = new MappIntelligenceDebugLogger(new MappIntelligenceDefaultLogger(), MappIntelligenceLogLevel::DEBUG);

        $logger->debug("debug1");
        $logger->debug("%s %s", "debug2", "debug3");

        $fileContent = join("\n", MappIntelligenceUnitUtil::getErrorLog());
        $this->assertContainsExtended("DEBUG [Mapp Intelligence]: debug1", $fileContent);
        $this->assertContainsExtended("DEBUG [Mapp Intelligence]: debug2 debug3", $fileContent);
    }
}
