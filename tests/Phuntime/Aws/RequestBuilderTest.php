<?php
declare(strict_types=1);

namespace Phuntime\Aws;


use PHPUnit\Framework\TestCase;

class RequestBuilderTest extends TestCase
{

    use AwsProvidersTrait;

    public function testNullQueryStrings()
    {
        $event = $this->getApiGatewayEvent(2);


//        $requestBuilder = new RequestBuilder();

        $request = RequestBuilder::buildPsr7Request($event);

        $this->assertCount(0, $request->getQueryParams());
    }

    public function testQueryStringMerging()
    {
        $event = $this->getApiGatewayEvent(1);

        $request = RequestBuilder::buildPsr7Request($event);

        $queryStringParams = $request->getQueryParams();

        $expected = [
            'foo' => 'bar',
            'choice' => ['beamer', 'benz', 'bentley']
        ];
        $this->assertEquals($expected, $queryStringParams);

    }
}