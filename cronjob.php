<?php
// phpcs:disable PSR1.Files.SideEffects

require_once __DIR__ . '/lib/MappIntelligenceCronjob.php';

$status = 1;
try {
    $cronjob = new MappIntelligenceCronjob(getopt(
        'i:d:f:c:',
        array('trackId:', 'trackDomain:', 'filename:', 'config:', 'debug')
    ));

    $status = $cronjob->run();
} catch (Exception $e) {
    // do nothing
}

exit($status);
