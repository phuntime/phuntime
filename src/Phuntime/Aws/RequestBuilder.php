<?php
declare(strict_types=1);

namespace Phuntime\Aws;

use Nyholm\Psr7\Request;
use Nyholm\Psr7\ServerRequest;
use Psr\Http\Message\ServerRequestInterface;

/**
 * @author pizzaminded <mikolajczajkowsky@gmail.com>
 * @license MIT
 */
class RequestBuilder
{

    /**
     * @param array $apiGatewayEvent
     * @return ServerRequestInterface
     */
    public static function buildPsr7Request(array $apiGatewayEvent): ServerRequestInterface
    {
        //TODO build _SERVER superglobal wannabe and pass them rest of required things
        //TODO build full Uri object with host and stuff

        $request = new ServerRequest(
            $apiGatewayEvent['httpMethod'],
            $apiGatewayEvent['path'],
            $apiGatewayEvent['headers'],
            $apiGatewayEvent['body'],
            $apiGatewayEvent['requestContext']['protocol']
        );


        $unifiedQueryParameters = array_merge(
            $apiGatewayEvent['multiValueQueryStringParameters'] ?? [],
            $apiGatewayEvent['queryStringParameters'] ?? []
        );

        $request = $request
            ->withQueryParams($unifiedQueryParameters)
            ->withAttribute('REQUEST_ID', $apiGatewayEvent['requestContext']['requestId']);

        return $request;
    }
}