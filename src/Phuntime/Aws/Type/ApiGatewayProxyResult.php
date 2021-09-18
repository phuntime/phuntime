<?php
declare(strict_types=1);

namespace Phuntime\Aws\Type;

use stdClass;
use function count;

/**
 * @see https://github.com/DefinitelyTyped/DefinitelyTyped/blob/master/types/aws-lambda/trigger/api-gateway-proxy.d.ts
 */
class ApiGatewayProxyResult
{
    protected int $statusCode;
    protected array $headers = [];
    protected array $multiValueHeaders = [];
    protected array $cookies = [];
    protected string $body;
    protected bool $base64Encoded;

    public function toArray(): array
    {
        /*
         * A temporary workaround for APIGW integration:
         * When there is no headers sent from FPM, an empty headers array is sent.
         * PHP serializes empty array to... empty array in JSON, but API Gateway expects an object in headers key.
         * So we need to put anything to force headers to be serialized as object.
         */
        $headers = new stdClass();
        $multiValueHeaders = new stdClass();

        if(count($this->headers) > 0) {
            $headers = $this->headers;
        }
        if(count($this->multiValueHeaders) > 0) {
            $multiValueHeaders = $this->multiValueHeaders;
        }

        $output = [
            'statusCode' => $this->statusCode,
            'multiValueHeaders' => $multiValueHeaders,
            'headers' => $headers,
            'body' => $this->body,
            'isBase64Encoded' => $this->base64Encoded,
        ];

        if(count($this->cookies) > 0) {
            $output['cookies'] = $this->cookies;
        }

        return $output;
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

    /**
     * @param array $cookies
     */
    public function setCookies(array $cookies): void
    {
        $this->cookies = $cookies;
    }
}