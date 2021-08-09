<?php
declare(strict_types=1);

namespace Phuntime\Bridge\Aws;


use Phuntime\Aws\Type\ApiGatewayProxyEvent;
use Phuntime\Aws\Type\ApiGatewayProxyResult;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * @license MIT
 */
class ApiGatewayPsrBridge
{

    public function __construct(
        private RequestFactoryInterface $requestFactory
    )
    {}

    public function apiGwToPsr7Request(ApiGatewayProxyEvent $event): RequestInterface
    {
        return $this->requestFactory->createRequest(
            $event->getHttpMethod(),
            $event->buildUrl()
        );
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