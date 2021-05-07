<?php
declare(strict_types=1);

namespace Phuntime\Aws\Extension;

use PHPUnit\Framework\TestCase;
use Phuntime\Aws\Type\ApiGatewayProxyEvent;
use Phuntime\Aws\Type\ApiGatewayV2ProxyEvent;
use Phuntime\Aws\Type\S3Event;
use Phuntime\UnitTestHelper;

class ApiGatewayToHttpFoundationExtensionTest extends TestCase
{

    public function testApiGwV1EventIsSupported()
    {
        $ext = new ApiGatewayToHttpFoundationExtension();
        self::assertTrue($ext->supports(new ApiGatewayProxyEvent()));
    }

    public function testApiGwV2EventIsSupported()
    {
        $ext = new ApiGatewayToHttpFoundationExtension();
        self::assertTrue($ext->supports(new ApiGatewayV2ProxyEvent()));
    }

    public function testRandomOtherEventIsNotSupported()
    {
        $ext = new ApiGatewayToHttpFoundationExtension();
        self::assertFalse($ext->supports(new S3Event()));
    }

    public function testNonSupportedEventException()
    {
        $ext = new ApiGatewayToHttpFoundationExtension();

        $this->expectException(\InvalidArgumentException::class);
        $this->expectDeprecationMessage('Phuntime\Aws\Extension\ApiGatewayToHttpFoundationExtension expects instance of Phuntime\Aws\Type\ApiGatewayProxyEvent or Phuntime\Aws\Type\ApiGatewayV2ProxyEvent, Phuntime\Aws\Type\S3Event given');
        $ext->transformFromRuntime(new S3Event());
    }

    public function testBasicTransformFromV2Runtime()
    {
        $ext = new ApiGatewayToHttpFoundationExtension();
        $payload = UnitTestHelper::getJsonFixture('aws-apigateway-v2-event-1');
        $evt = ApiGatewayV2ProxyEvent::fromArray($payload);
        $httpFoundationRequest = $ext->transformFromRuntime($evt);

        self::assertSame('POST', $httpFoundationRequest->getMethod());
        self::assertSame('/my/path2', $httpFoundationRequest->getPathInfo());
    }

    public function testBasicTransformFromV1Runtime()
    {
        $ext = new ApiGatewayToHttpFoundationExtension();
        $payload = UnitTestHelper::getJsonFixture('aws-apigateway-v1-event-1');
        $evt = ApiGatewayProxyEvent::fromArray($payload);
        $httpFoundationRequest = $ext->transformFromRuntime($evt);

        self::assertSame('GET', $httpFoundationRequest->getMethod());
        self::assertSame('/my/path1', $httpFoundationRequest->getPathInfo());
    }

}