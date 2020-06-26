<?php
declare(strict_types=1);


$vendorPathsToScan = [
    getcwd() . '/vendor/autoload.php',
    __DIR__ . '/../vendor/autoload.php'
];

foreach ($vendorPathsToScan as $path) {
    if (file_exists($path)) {
        /** @noinspection PhpIncludeInspection */
        include_once $path;
        break;
    }
}


$runtime = \Phuntime\Local\LocalRuntime::create();
$handler = \Phuntime\Core\Handler::fromRuntime($runtime);

$handler->boot();
$request = $runtime->getNextRequest();
if ($request instanceof \Psr\Http\Message\ServerRequestInterface) {
    $request = $request->withAttribute('REQUEST_ID', md5(microtime()));
}
$handler->handleEvent($request);

