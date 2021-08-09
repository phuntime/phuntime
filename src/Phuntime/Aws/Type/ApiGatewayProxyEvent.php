<?php
declare(strict_types=1);

namespace Phuntime\Aws\Type;


use Phuntime\Core\Contract\EventInterface;

/**
 * @see https://github.com/DefinitelyTyped/DefinitelyTyped/blob/master/types/aws-lambda/trigger/api-gateway-proxy.d.ts
 * @see https://docs.aws.amazon.com/apigateway/latest/developerguide/http-api-develop-integrations-lambda.html
 * @license MIT
 */
class ApiGatewayProxyEvent implements EventInterface
{

    protected array $payload;
    protected string $httpVersion;
    protected string $path;
    protected string $domainName;
    protected string $requestId;

    public static function fromArray(array $payload): self
    {
        $object = new static();
        $object->httpVersion = $payload['httpMethod'];
        $object->path = $payload['path'];
        $object->domainName = $payload['requestContext']['domainName'];
        $object->requestId = $payload['requestContext']['requestId'];

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

    /**
     * @return string
     */
    public function getDomainName(): string
    {
        return $this->domainName;
    }

    public function isAsync(): bool
    {
        return false;
    }

    /**
     * @return string
     */
    public function buildUrl(): string
    {
       return sprintf('https://%s%s', $this->domainName, $this->path);
    }

    /**
     * @return string
     */
    public function getRequestId(): string
    {
        return $this->requestId;
    }
}