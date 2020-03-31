<?php
// phpcs:disable PSR1.Files.SideEffects

require_once __DIR__ . '/lib/MappIntelligenceServer2Server.php';

$status = 1;
try {
    $s2s = new MappIntelligenceServer2Server(array(
        'filename' => './tmp/webtrekk.log',
        'debug' => true
    ));

    $s2s->run();
} catch (Exception $e) {
    // do nothing
}
