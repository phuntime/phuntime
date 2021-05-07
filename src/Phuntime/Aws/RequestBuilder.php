<?php
declare(strict_types=1);

namespace Phuntime\Aws;

use Nyholm\Psr7\ServerRequest;
use Nyholm\Psr7\Uri;
use Psr\Http\Message\ServerRequestInterface;

/**
 * @author pizzaminded <mikolajczajkowsky@gmail.com>
 * @license MIT
 * @deprecated to be refactored to some extension
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


        $unifiedQueryParameters = [];
        $multiValueQueryParams = $apiGatewayEvent['multiValueQueryStringParameters'] ?? [];
        $singleQueryParams = $apiGatewayEvent['queryStringParameters'] ?? [];

        /**
         * Merge and normalize multi value query parameters with single one
         * @see tests/fixtures/aws-apigateway-event-1.json for example from Gateway
         */
        foreach ($singleQueryParams as $queryParameterKey => $unifiedQueryParameter) {
            $singleElement = $singleQueryParams[$queryParameterKey];
            $multiElement = $multiValueQueryParams[$queryParameterKey];
            $keyEndsWithBrackets = str_ends_with($queryParameterKey, '[]');

            /**
             * QS Argument is single, no need to tweaks
             */
            if (count($multiElement) === 1 && !$keyEndsWithBrackets) {
                $unifiedQueryParameters[$queryParameterKey] = $singleElement;
                continue;
            }

            /**
             * In this case, query parameter is multi-value and got "[]" suffix
             * its required to trim them to comply with eg. $_GET output
             */
            $newKey = substr($queryParameterKey, 0, -2);
            $unifiedQueryParameters[$newKey] = $multiElement;
        }


        $request = $request->withQueryParams($unifiedQueryParameters);

        return $request;
    }
}