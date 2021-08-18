<?php
declare(strict_types=1);

namespace Phuntime\Aws;


use PHPUnit\Framework\TestCase;
use Phuntime\UnitTestHelper;

class EventClassifierTest extends TestCase
{

    public function badV1ApiGwEventsProvider(): array
    {
        return [
            [UnitTestHelper::getJsonFixture('invalid/apigw-empty')],
            [UnitTestHelper::getJsonFixture('invalid/apigw-http-method')],
            [UnitTestHelper::getJsonFixture('invalid/apigw-multi-qs-null')],
            [UnitTestHelper::getJsonFixture('invalid/apigw-multi-qs-string')],
            [UnitTestHelper::getJsonFixture('invalid/apigw-no-version')],
            [UnitTestHelper::getJsonFixture('invalid/apigw-version-invalid')],
        ];
    }

    /**
     * @dataProvider badV1ApiGwEventsProvider
     * @param array $payload
     */
    public function testApiGwV1MatcherWillNotMatchInvalidEvents(array $payload)
    {
        $classifier = new EventClassifier();

        self::assertFalse($classifier->isApiGatewayV1ProxyEvent($payload));
    }

    public function testThereWillBeApiGwV1Matched()
    {
        $payload = UnitTestHelper::getJsonFixture('aws-apigateway-v1-event-1');
        $classifier = new EventClassifier();

        self::assertTrue($classifier->isApiGatewayV1ProxyEvent($payload));
    }

    public function testApiGwV1MatcherWillNotMatchV2()
    {
        $payload = UnitTestHelper::getJsonFixture('aws-apigateway-v2-event-1');
        $classifier = new EventClassifier();

        self::assertFalse($classifier->isApiGatewayV1ProxyEvent($payload));
    }

    public function testApiGwV2MatcherWillNotMatchV1()
    {
        $payload = UnitTestHelper::getJsonFixture('aws-apigateway-v1-event-1');
        $classifier = new EventClassifier();

        self::assertFalse($classifier->isApiGatewayV2ProxyEvent($payload));
    }
}