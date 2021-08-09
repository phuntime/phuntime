<?php

if(!extension_loaded('swoole')) {
    echo 'Swoole required!';
    die(1);
}

$http = new swoole_http_server('127.0.0.1', 9001);
$http->on('request', function (\Swoole\Http\Request $request, \Swoole\Http\Response $response) {
    $requestId = md5(uniqid('', true));
    $method = $request->server['request_method'];
    $pathInfo = $request->server['path_info'];

    error_log(sprintf('[info] request: %s %s', $method, $pathInfo));

    if($method === 'GET' && $pathInfo === '/2018-06-01/runtime/invocation/next') {
        error_log('[info] AWS RIE matched');
        error_log(sprintf('[debug] request id: %s', $requestId));

        $requestJson = json_decode(file_get_contents(__DIR__.'/../../tests/fixtures/aws-apigateway-v1-event-1.json'), true);
        $requestJson['requestContext']['requestId'] = $requestId;


        $response->header("Content-Type", "application/json");
        $response->end(json_encode($requestJson));
    }

    if($method === 'POST' && str_starts_with($pathInfo, '/2018-06-01/runtime/invocation/') && str_ends_with($pathInfo, '/response')) {

        error_log('[info] AWS RIE matched');
        error_log($request->getContent());
        $response->header("Content-Type", "application/json");
        $response->setStatusCode(200);
        $response->end('{]');
    }

});

$http->start();