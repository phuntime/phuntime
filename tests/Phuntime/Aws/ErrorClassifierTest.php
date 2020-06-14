<?php
declare(strict_types=1);

namespace Phuntime\Aws;


use PHPUnit\Framework\TestCase;

class ErrorClassifierTest extends TestCase
{

    use AwsProvidersTrait;


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