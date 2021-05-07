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

    public static function fromArray(array $payload): self
    {
        $object = new static();
        $object->httpVersion = $payload['requestContext']['http']['method'];

        return $object;
    }



    public function getVersion(): string
    {
        return '2.0';
    }
}