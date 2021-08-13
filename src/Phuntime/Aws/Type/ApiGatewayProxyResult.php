<?php
declare(strict_types=1);

namespace Phuntime\Aws\Type;

/**
 * @see https://github.com/DefinitelyTyped/DefinitelyTyped/blob/master/types/aws-lambda/trigger/api-gateway-proxy.d.ts
 */
class ApiGatewayProxyResult
{
    protected int $statusCode;
    protected array $headers = [];
    protected array $multiValueHeaders = [];
    protected string $body;
    protected bool $base64Encoded;

    public function toArray(): array
    {
        $multiValueHeaders = $this->multiValueHeaders;
        if (count($multiValueHeaders) > 0) {
            $multiValueHeaders = $this->headers;
        }

        return [
            'statusCode' => $this->statusCode,
            'headers' => $this->headers,
            'body' => $this->body,
            'isBase64Encoded' => $this->base64Encoded
        ];
    }

    /**
     * @param int $statusCode
     */
    public function setStatusCode(int $statusCode): void
    {
        $this->statusCode = $statusCode;
    }

    /**
     * @param array $headers
     */
    public function setHeaders(array $headers): void
    {
        $this->headers = $headers;
    }

    /**
     * @param array $multiValueHeaders
     */
    public function setMultiValueHeaders(array $multiValueHeaders): void
    {
        $this->multiValueHeaders = $multiValueHeaders;
    }

    /**
     * @param string $body
     */
    public function setBody(string $body): void
    {
        $this->body = $body;
    }

    /**
     * @param bool $base64Encoded
     */
    public function setBase64Encoded(bool $base64Encoded): void
    {
        $this->base64Encoded = $base64Encoded;
    }


}