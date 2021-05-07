<?php
declare(strict_types=1);

namespace Phuntime\Aws\Extension;

use PHPUnit\Framework\TestCase;
use Phuntime\Aws\Type\ApiGatewayProxyEvent;
use Phuntime\Aws\Type\ApiGatewayV2ProxyEvent;
use Phuntime\Aws\Type\S3Event;

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

}