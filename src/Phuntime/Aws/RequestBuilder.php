<?php
declare(strict_types=1);

namespace Phuntime\Aws;

use Nyholm\Psr7\ServerRequest;
use Nyholm\Psr7\Uri;
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
        $uri = (new Uri())
            ->withPath($apiGatewayEvent['path'])
            ->withPort($apiGatewayEvent['headers']['X-Forwarded-Port'])
            ->withScheme($apiGatewayEvent['headers']['X-Forwarded-Proto'])
            ->withHost($apiGatewayEvent['headers']['Host']);

        $body = $apiGatewayEvent['body'];
        if ($apiGatewayEvent['isBase64Encoded']) {
            $body = base64_decode($body, true);
        }

        $request = new ServerRequest(
            $apiGatewayEvent['httpMethod'],
            $uri,
            $apiGatewayEvent['headers'],
            $body,
            $apiGatewayEvent['requestContext']['protocol'] ?? '1.1'
        );

        $unifiedQueryParameters = array_merge(
            $apiGatewayEvent['multiValueQueryStringParameters'] ?? [],
            $apiGatewayEvent['queryStringParameters'] ?? []
        );

        /**
         * AWS SAM passes multi value Query string parameters with a "[]" suffix, we need to remove it.
         * @TODO verify that this situation should happen on "production" AWS Lambda
         * @see tests/fixtures/aws-apigateway-event-3.json for example from Gateway
         */
        foreach ($unifiedQueryParameters as $queryParameterKey => $unifiedQueryParameter) {
            if (str_ends_with($queryParameterKey, '[]')) {
                unset($unifiedQueryParameters[$queryParameterKey]);
                $newKey = substr($queryParameterKey, 0, -2);
                $unifiedQueryParameters[$newKey] = $unifiedQueryParameter;
            }
        }


        $request = $request->withQueryParams($unifiedQueryParameters);

        return $request;
    }
}