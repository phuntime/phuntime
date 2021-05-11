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
            'multiValueHeaders' => $multiValueHeaders,
            'body' => $this->body,
            'isBase64Encoded' => $this->base64Encoded
        ];
    }
}