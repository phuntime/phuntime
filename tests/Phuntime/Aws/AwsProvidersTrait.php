<?php
declare(strict_types=1);

namespace Phuntime\Aws;


trait AwsProvidersTrait
{
    public function apiGatewayEvents(): array
    {
        return [
            [json_decode(file_get_contents(__DIR__ . '/../../fixtures/aws-apigateway-event-1.json'), true)],
            [json_decode(file_get_contents(__DIR__ . '/../../fixtures/aws-apigateway-event-2.json'), true)]
        ];
    }

    public function getApiGatewayEvent(int $number)
    {
        return json_decode(file_get_contents(__DIR__ . '/../../fixtures/aws-apigateway-event-' . $number . '.json'), true);
    }
}