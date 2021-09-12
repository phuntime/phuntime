<?php
declare(strict_types=1);

namespace Phuntime\Aws\Type;

use Phuntime\Core\Contract\EventInterface;

/**
 * @see https://github.com/DefinitelyTyped/DefinitelyTyped/blob/master/types/aws-lambda/trigger/api-gateway-proxy.d.ts
 * @see https://docs.aws.amazon.com/apigateway/latest/developerguide/http-api-develop-integrations-lambda.html
 * @license MIT
 */
class ApiGatewayV2ProxyEvent implements EventInterface
{

    protected string $httpMethod;
    protected string $httpVersion;
    protected string $path;
    protected string $domainName;
    protected string $rawQueryString;

    /**
     * @psalm-pure
     * @param array $payload
     * @return static
     */
    public static function fromArray(array $payload): self
    {
        $object = new self();
        $object->httpMethod = $payload['requestContext']['http']['method'];
        $object->path = $payload['requestContext']['http']['path'];
        $object->domainName = $payload['requestContext']['domainName'];
        $object->rawQueryString = $payload['rawQueryString'];

        return $object;
    }


    /**
     * @deprecated
     * @return string
     */
    public function getVersion(): string
    {
        return '2.0';
    }

    public function isAsync(): bool
    {
        return false;
    }

    /**
     * @return string
     */
    public function getHttpVersion(): string
    {
        return $this->httpVersion;
    }

    /**
     * @return string
     */
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

    /**
     * @return string
     */
    public function getHttpMethod(): string
    {
        return $this->httpMethod;
    }

    /**
     * @return string
     */
    public function buildUrl(): string
    {
        return sprintf('https://%s%s?%s', $this->domainName, $this->path, $this->rawQueryString);
    }


}