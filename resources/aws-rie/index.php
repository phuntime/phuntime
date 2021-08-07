<?php

if(!extension_loaded('swoole')) {
    echo 'Swoole required!';
    die(1);
}

$http = new swoole_http_server('127.0.0.1', 9001);
$http->on('request', function (\Swoole\Http\Request $request, \Swoole\Http\Response $response) {
    $method = $request->server['request_method'];
    $pathInfo = $request->server['path_info'];

    error_log(sprintf('[debug] request: %s %s', $method, $pathInfo));

    if($method === 'GET' && $pathInfo === '/2018-06-01/runtime/invocation/next') {
        $response->header("Content-Type", "application/json");
        $response->end(file_get_contents(__DIR__.'/../../tests/fixtures/aws-apigateway-v1-event-1.json'));
    }

});

$http->start();