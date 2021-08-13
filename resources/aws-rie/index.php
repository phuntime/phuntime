<?php

if(!extension_loaded('swoole')) {
    echo 'Swoole required!';
    die(1);
}

$store = new \PDO(sprintf('sqlite:%s', __DIR__.'/rie.db'));
$store->exec('
    DROP TABLE IF EXISTS events;
    CREATE TABLE events (
        id TEXT PRIMARY KEY,
        method VARCHAR(10),
        path_info VARCHAR(4096),
        processing INT,
        response_code INT,
        response_body TEXT,
        response_headers TEXT,
        query_string TEXT
    );
');

$http = new swoole_http_server('127.0.0.1', 9001);
$http->on('request', function (\Swoole\Http\Request $request, \Swoole\Http\Response $response) use ($store) {
    $requestId = (string)microtime(true);

    $method = $request->server['request_method'];
    $pathInfo = $request->server['path_info'];

    print(sprintf('[info] request: %s %s id: %s', $method, $pathInfo, $requestId).PHP_EOL);

    if($method === 'GET' && $pathInfo === '/2018-06-01/runtime/invocation/next') {

        print('[info] AWS RIE matched'.PHP_EOL);

        do {
            $reqStmt = $store->query('SELECT * FROM events WHERE id IS NOT NULL AND processing = 0 ORDER by id LIMIT 1');
            $req = $reqStmt->fetch(\PDO::FETCH_ASSOC);
        } while($req === false || count($req) === 0);

        $reqId = $req['id'];
        print('[info] Got request'.PHP_EOL);
        print(sprintf('[debug] passing req id: %s', $reqId ).PHP_EOL);
        $store->exec('UPDATE events SET processing = 1 WHERE id = '.$reqId);
        print(sprintf('[debug] acking req id: %s', $reqId ).PHP_EOL);

        /** @var array $requestJson */
        $requestJson = json_decode(file_get_contents(__DIR__ . '/../../tests/fixtures/aws-apigateway-v1-event-1.json'), true, 512, JSON_THROW_ON_ERROR);
        $requestJson['requestContext']['requestId'] = $reqId;
        $requestJson['multiValueQueryStringParameters'] = json_decode($req['query_string'], true, 512, JSON_THROW_ON_ERROR);

        $response->header("Content-Type", "application/json");
        $response->header('lambda-runtime-aws-request-id', $reqId);
        $response->end(json_encode($requestJson, JSON_THROW_ON_ERROR));
        return;

    }

    if($method === 'POST' && str_starts_with($pathInfo, '/2018-06-01/runtime/invocation/') && str_ends_with($pathInfo, '/response')) {
        print('[info] AWS RIE matched'.PHP_EOL);
        $rq = str_replace(['/2018-06-01/runtime/invocation/','/response'], '',$pathInfo);
        print(sprintf('[info] Got a response for %s', $rq).PHP_EOL);

        $json = json_decode($request->getContent(), true);


        $resStmt = $store->prepare('UPDATE events SET response_code = :res, response_body = :body, response_headers = :hed, processing = 2 WHERE id = :id');
        $resStmt->execute([
            'id' => $rq,
            'res' => $json['statusCode'],
            'body' => $json['body'],
            'hed' => json_encode($json['headers'])
        ]);

        $response->header("Content-Type", "application/json");
        $response->setStatusCode(200);
        $response->end('{]');
        return;
    }

    $stmt = $store->query(
        'INSERT INTO events(id, method, path_info, processing, query_string ) VALUES (:id, :m, :pi, 0, :qs)'
    );

    $stmt->execute([
        'id' => $requestId,
        'm' => $method,
        'pi' => $pathInfo,
        'qs' => json_encode($request->get)
    ]);


    print(sprintf('[debug] waiting for response for req id: %s', $requestId ).PHP_EOL);
    do {
        $resStmt = $store->prepare('SELECT * FROM events WHERE id = :id AND processing = 2');
        $resStmt->execute([
            'id' => $requestId,
        ]);
        $res = $resStmt->fetch(\PDO::FETCH_ASSOC);
    } while($res === false || count($res) === 0);

    $response->setStatusCode((int)$res['response_code']);
    $response->write($res['response_body']);
    $response->end();
});

$http->start();