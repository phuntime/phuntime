<?php
declare(strict_types=1);

namespace Phuntime\Aws\Type;


use PHPUnit\Framework\TestCase;
use Phuntime\UnitTestHelper;

class ApiGatewayProxyEventTest extends TestCase
{
    public function testValidVersionIsReturned(): void
    {
        $payload = UnitTestHelper::getJsonFixture('aws-apigateway-v1-event-1');
        $event = ApiGatewayProxyEvent::fromArray($payload);
        self::assertSame('1.0', $event->getVersion());
    }

    public function testHttpMethod(): void
    {
        $payload = UnitTestHelper::getJsonFixture('aws-apigateway-v1-event-1');
        $event = ApiGatewayProxyEvent::fromArray($payload);
        self::assertSame('GET', $event->getHttpMethod());
    }

    public function testHttpPath()
    {
        $payload = UnitTestHelper::getJsonFixture('aws-apigateway-v1-event-1');
        $event = ApiGatewayProxyEvent::fromArray($payload);
        self::assertSame('/my/path1', $event->getPath());
    }

    public function testHttpHost(): void
    {
        $payload = UnitTestHelper::getJsonFixture('aws-apigateway-v1-event-1');
        $event = ApiGatewayProxyEvent::fromArray($payload);
        self::assertSame('id.execute-api.us-east-1.amazonaws.com', $event->getDomainName());
    }

    public function testEventIsNotAsync(): void
    {
        $payload = UnitTestHelper::getJsonFixture('aws-apigateway-v1-event-1');
        $event = ApiGatewayProxyEvent::fromArray($payload);
        self::assertFalse($event->isAsync());
    }

}