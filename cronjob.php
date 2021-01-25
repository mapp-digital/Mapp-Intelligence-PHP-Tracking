<?php
// phpcs:disable PSR1.Files.SideEffects

require_once __DIR__ . '/lib/Cronjob/MappIntelligenceCLICronjob.php';

$status = 1;
try {
    $cronjob = new MappIntelligenceCLICronjob(getopt(
        'i:d:t:f:c:p:',
        array(
            'trackId:', 'trackDomain:', 'consumerType:',
            'filename:', 'filePath:', 'filePrefix:',
            'config:',
            'debug', 'version', 'help'
        )
    ));

    $status = $cronjob->run();
} catch (Exception $e) {
    // do nothing
}

exit($status);
