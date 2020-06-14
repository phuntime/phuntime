<?php
declare(strict_types=1);

namespace Phuntime\Aws;


use PHPUnit\Framework\TestCase;

class ErrorClassifierTest extends TestCase
{

    public function apiGatewayEvents(): array
    {
        return [
            [json_decode(file_get_contents(__DIR__ . '/../../fixtures/aws-apigateway-event-1.json'), true)]
        ];
    }


    /**
     * @dataProvider apiGatewayEvents
     * @param array $apiGatewayEvent
     */
    public function testApiGatewayClassification(array $apiGatewayEvent)
    {
        $classifier = new EventClassifier();

        $this->assertTrue($classifier->isApiGatewayProxyEvent($apiGatewayEvent));
    }
}