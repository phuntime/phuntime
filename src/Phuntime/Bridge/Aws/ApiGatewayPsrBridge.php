<?php
declare(strict_types=1);

namespace Phuntime\Bridge\Aws;


use Phuntime\Aws\Type\ApiGatewayProxyEvent;
use Phuntime\Aws\Type\ApiGatewayProxyResult;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\RequestInterface;
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
     * @param ApiGatewayProxyEvent $event
     * @return ServerRequestInterface
     */
    public function apiGwToPsr7Request(ApiGatewayProxyEvent $event): ServerRequestInterface
    {
        return
            $this->requestFactory->createServerRequest(
                $event->getHttpMethod(),
                $event->buildUrl()
            )->withQueryParams($event->getMultiValueQueryStringParameters());
    }

    public function psr7ResponseToApiGw(ResponseInterface $response): ApiGatewayProxyResult
    {
        $result = new ApiGatewayProxyResult();
        $result->setBody((string)$response->getBody());
        $result->setStatusCode($response->getStatusCode());
        $result->setBase64Encoded(false);


        return $result;
    }

}