<?php
declare(strict_types=1);

/** @noinspection PhpIncludeInspection */
include_once getcwd().'/vendor/autoload.php';



$runtime = \Phuntime\Local\LocalRuntime::create();
$handler = \Phuntime\Core\Handler::fromRuntime($runtime);

$handler->boot();
$request = $runtime->getNextRequest();
if($request instanceof \Psr\Http\Message\ServerRequestInterface) {
   $request =  $request->withAttribute('REQUEST_ID', md5(microtime()));
}
$handler->handleEvent($request);

