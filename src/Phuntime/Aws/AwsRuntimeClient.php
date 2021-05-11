<?php
declare(strict_types=1);

namespace Phuntime\Aws;

use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\HttpClient\HttpClientInterface;

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

}