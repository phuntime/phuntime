<?php
declare(strict_types=1);

namespace Phuntime\Bridge\Aws;


use Phuntime\Aws\Type\ApiGatewayProxyEvent;
use Phuntime\Aws\Type\ApiGatewayProxyResult;
use Phuntime\Aws\Type\ApiGatewayV2ProxyEvent;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestFactoryInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * @license MIT
 */
class ApiGatewayPsrBridge
{

    public function __construct(
        private ServerRequestFactoryInterface $requestFactory
    )
    {}

    /**
     * @TODO: add tests for v2 event
     * @param ApiGatewayProxyEvent|ApiGatewayV2ProxyEvent $event
     * @return ServerRequestInterface
     */
    public function apiGwToPsr7Request(ApiGatewayProxyEvent|ApiGatewayV2ProxyEvent $event): ServerRequestInterface
    {
        $qs = [];

        if($event instanceof ApiGatewayProxyEvent) {
            $qs = $event->getMultiValueQueryStringParameters();
        }

        if($event instanceof ApiGatewayV2ProxyEvent) {
            $qs = $event->getQueryStringParameters();
        }

        return
            $this->requestFactory->createServerRequest(
                $event->getHttpMethod(),
                $event->buildUrl()
            )->withQueryParams($qs);
    }

    public function psr7ResponseToApiGw(ResponseInterface $response): ApiGatewayProxyResult
    {
        $result = new ApiGatewayProxyResult();
        $result->setBody((string)$response->getBody());
        $result->setStatusCode($response->getStatusCode());
        $result->setBase64Encoded(false);
        $result->setMultiValueHeaders($response->getHeaders());


        return $result;
    }

}