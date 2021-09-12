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
    protected string $httpMethod;
    protected string $path;
    protected string $domainName;
    protected string $requestId;
    protected array $multiValueQueryStringParameters;
    protected array $queryStringParameters;

    /**
     * @psalm-pure
     * @param array $payload
     * @psalm-param array{httpMethod: string} $payload
     * @return static
     */
    public static function fromArray(array $payload): self
    {
        $object = new self();
        $object->httpMethod = $payload['httpMethod'];
        $object->path = $payload['path'];
        $object->domainName = $payload['requestContext']['domainName'];
        $object->requestId = $payload['requestContext']['requestId'];
        $object->multiValueQueryStringParameters = $payload['multiValueQueryStringParameters'] ?? [];
        $object->queryStringParameters = $payload['queryStringParameters'] ?? [];

        return $object;
    }

    /**
     * @deprecated
     * @return string
     */
    public function getVersion(): string
    {
        return '1.0';
    }

    public function getHttpMethod(): string
    {
        return $this->httpMethod;
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
       return sprintf('https://%s%s?%s', $this->domainName, $this->path, http_build_query($this->multiValueQueryStringParameters));
    }

    /**
     * @return string
     */
    public function getRequestId(): string
    {
        return $this->requestId;
    }

    /**
     * @return array
     */
    public function getMultiValueQueryStringParameters(): array
    {
        return $this->multiValueQueryStringParameters;
    }

    /**
     * @return array
     */
    public function getQueryStringParameters(): array
    {
        return $this->queryStringParameters;
    }


}