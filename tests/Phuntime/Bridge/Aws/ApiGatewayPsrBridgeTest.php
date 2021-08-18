<?php
declare(strict_types=1);

namespace Phuntime\Bridge\Aws;


use Nyholm\Psr7\Factory\Psr17Factory;
use PHPUnit\Framework\TestCase;
use Phuntime\Aws\Type\ApiGatewayProxyEvent;
use Phuntime\UnitTestHelper;

class ApiGatewayPsrBridgeTest extends TestCase
{

    public function testApiGwToPsr7ConversionOnApiGwV1Event()
    {
        $psr17Factory = new Psr17Factory();
        $apiGwPsr = new ApiGatewayPsrBridge($psr17Factory);

        $apiGwEvent = ApiGatewayProxyEvent::fromArray(UnitTestHelper::getJsonFixture('aws-apigateway-v1-event-1'));
        $psr7Request = $apiGwPsr->apiGwToPsr7Request($apiGwEvent);

        self::assertEquals('/my/path1', $psr7Request->getUri()->getPath());
        self::assertEquals('GET', $psr7Request->getMethod());
        self::assertEquals('id.execute-api.us-east-1.amazonaws.com', $psr7Request->getUri()->getHost());
        self::assertSame([
            'parameter1' => ['value1', 'value2'],
            'parameter2' => ['value'],
        ], $psr7Request->getQueryParams());


    }

}