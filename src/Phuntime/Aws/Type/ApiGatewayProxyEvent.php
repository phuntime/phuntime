<?php
declare(strict_types=1);

namespace Phuntime\Aws\Type;


/**
 * @see https://github.com/DefinitelyTyped/DefinitelyTyped/blob/master/types/aws-lambda/trigger/api-gateway-proxy.d.ts
 * @see https://docs.aws.amazon.com/apigateway/latest/developerguide/http-api-develop-integrations-lambda.html
 * @license MIT
 */
class ApiGatewayProxyEvent
{

    protected array $payload;
    protected string $httpVersion;
    protected string $path;

    public static function fromArray(array $payload): self
    {
        $object = new static();
        $object->httpVersion = $payload['httpMethod'];
        $object->path = $payload['path'];

        return $object;
    }

    public function getVersion(): string
    {
        return '1.0';
    }

    public function getHttpMethod(): string
    {
        return $this->httpVersion;
    }

    public function getPath(): string
    {
        return $this->path;
    }

}