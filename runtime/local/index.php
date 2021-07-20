<?php
/**
 * Usage while dev:
 * cd ./runtime/local
 * FUNCTION_FILE=<path to web function file> php -S <ip>:<port>
 */

include_once __DIR__.'/../../vendor/autoload.php';
$functionPath = getenv('FUNCTION_PATH');

if((bool)$functionPath) {
    exit('There is no FUNCTION_PATH env defined, exiting');
}

$runtime = \Phuntime\Local\LocalRuntime::create();



//$evp = \Phuntime\Core\EventProcessor::