<?php
declare(strict_types=1);

namespace Phuntime\Aws\Type;


use PHPUnit\Framework\TestCase;
use Phuntime\UnitTestHelper;

class ApiGatewayV2ProxyEventTest extends TestCase
{

    public function testValidVersionIsReturned()
    {
        $payload = UnitTestHelper::getJsonFixture('aws-apigateway-v2-event-1');
        $event = ApiGatewayV2ProxyEvent::fromArray($payload);

        self::assertSame('2.0', $event->getVersion());
    }

    public function testHttpMethod()
    {
        $payload = UnitTestHelper::getJsonFixture('aws-apigateway-v2-event-1');
        $event = ApiGatewayV2ProxyEvent::fromArray($payload);
        self::assertSame('POST', $event->getHttpMethod());
    }

    public function testHttpPath()
    {
        $payload = UnitTestHelper::getJsonFixture('aws-apigateway-v2-event-1');
        $event = ApiGatewayV2ProxyEvent::fromArray($payload);
        self::assertSame('/my/path2', $event->getPath());
    }

    public function testHttpDomain()
    {
        $payload = UnitTestHelper::getJsonFixture('aws-apigateway-v2-event-1');
        $event = ApiGatewayV2ProxyEvent::fromArray($payload);
        self::assertSame('id.execute-api.eu-central-1.amazonaws.com', $event->getDomainName());
    }

    public function testEventIsNotAsync()
    {
        $payload = UnitTestHelper::getJsonFixture('aws-apigateway-v2-event-1');
        $event = ApiGatewayV2ProxyEvent::fromArray($payload);
        self::assertTrue($event->isAsync());
    }
}