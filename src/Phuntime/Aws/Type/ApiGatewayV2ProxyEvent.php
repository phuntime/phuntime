<?php
declare(strict_types=1);

namespace Phuntime\Aws\Type;

/**
 * @see https://github.com/DefinitelyTyped/DefinitelyTyped/blob/master/types/aws-lambda/trigger/api-gateway-proxy.d.ts
 * @see https://docs.aws.amazon.com/apigateway/latest/developerguide/http-api-develop-integrations-lambda.html
 * @license MIT
 */
class ApiGatewayV2ProxyEvent extends ApiGatewayProxyEvent
{

    /**
     * @psalm-pure
     * @param array $payload
     * @return static
     */
    public static function fromArray(array $payload): self
    {
        $object = new self();
        $object->httpVersion = $payload['requestContext']['http']['method'];
        $object->path = $payload['requestContext']['http']['path'];
        $object->domainName = $payload['requestContext']['domainName'];

        return $object;
    }



    public function getVersion(): string
    {
        return '2.0';
    }
}