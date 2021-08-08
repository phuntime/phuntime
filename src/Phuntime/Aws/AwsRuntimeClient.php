<?php
declare(strict_types=1);

namespace Phuntime\Aws;

use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;
use function get_class;
use function json_encode;
use function sprintf;
use function strtoupper;

/**
 * Knows how to talk with Lambda Runtime and brings them responses to his requests.
 * @license MIT
 */
class AwsRuntimeClient
{
    protected string $runtimeHost;
    protected HttpClientInterface $httpClient;

    public function __construct(
        string $runtimeHost,
        ?HttpClientInterface $httpClient = null
    )
    {
        $this->runtimeHost = $runtimeHost;
        $this->httpClient = $httpClient ?? HttpClient::create();
    }

    /**
     * @param string $method
     * @param string $path
     * @param string|null $body
     * @param string $contentType
     * @return ResponseInterface
     * @throws TransportExceptionInterface
     */
    protected function request(string $method, string $path, ?string $body = null, string $contentType = 'text/plain'): ResponseInterface
    {
        $method = strtoupper($method);

        $url = sprintf(
            'http://%s/2018-06-01/runtime/%s',
            $this->runtimeHost,
            $path
        );

        return $this->httpClient->request($method, $url, [
            'body' => $body,
            'headers' => [
                'Content-Type' => $contentType
            ],
        ]);
    }

    /**
     * @param \Throwable $exception
     * @param string|null $requestId
     * @throws TransportExceptionInterface|\JsonException
     */
    public function handleInvocationError(\Throwable $exception, string $requestId = null): void
    {
        $output = [
            'errorMessage' => sprintf('InvocationError Occurred: "%s"', $exception->getMessage()),
            'errorType' => get_class($exception)
        ];

        $output = json_encode($output, JSON_THROW_ON_ERROR);
        $this->request('POST', 'invocation/' . $requestId . '/error', $output, 'application/json');
    }

    /**
     * @param \Throwable $throwable
     * @throws TransportExceptionInterface
     * @throws \JsonException
     */
    public function handleInitializationException(\Throwable $throwable): void
    {
        $output = [
            'errorMessage' => sprintf('InitializationException Occured: "%s"', $throwable->getMessage()),
            'errorType' => get_class($throwable)
        ];

        $output = json_encode($output, JSON_THROW_ON_ERROR);
        $this->request('POST', 'init/error', $output, 'application/json');
    }


    /**
     * @throws RedirectionExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws ClientExceptionInterface
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     */
    public function getEvent(): array
    {
        $request = $this->request('GET', 'invocation/next');

        return [
            $request->toArray(false),
            $request->getHeaders(false)
        ];

    }

    /**
     * @param string $eventId
     * @param array $payload
     * @throws TransportExceptionInterface
     * @throws \JsonException
     */
    public function respondToEvent(string $eventId, array $payload): void
    {
        $this->request(
            'POST',
            sprintf('invocation/%s/response', $eventId),
            json_encode($payload, JSON_THROW_ON_ERROR),
            'application/json');
    }
}