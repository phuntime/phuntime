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

    public function testUriContainsAllElements()
    {
        $event = $this->getApiGatewayEvent(1);

        $request = RequestBuilder::buildPsr7Request($event);

        $this->assertEquals('1234567890.execute-api.eu-central-1.amazonaws.com', $request->getUri()->getHost());
        $this->assertEquals('/path/to/resource', $request->getUri()->getPath());

    }


    /**
     * @dataProvider apiGatewayEvents
     * @param array $apiGatewayEvent
     */
    public function testBase64Encoding(array $apiGatewayEvent)
    {
        $request = RequestBuilder::buildPsr7Request($apiGatewayEvent);
        $this->assertEquals('{"test":"body"}', (string)$request->getBody());
    }

}