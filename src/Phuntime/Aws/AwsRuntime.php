<?php
declare(strict_types=1);

namespace Phuntime\Aws;


use Phuntime\Core\ContextInterface;
use Phuntime\Core\RuntimeInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Log\LoggerInterface;

class AwsRuntime implements RuntimeInterface
{

    public function getLogger(): LoggerInterface
    {
        // TODO: Implement getLogger() method.
    }

    public function getNextRequest(): ServerRequestInterface
    {
        // TODO: Implement getNextRequest() method.
    }

    public function respondToRequest(string $requestId, ResponseInterface $response): void
    {
        // TODO: Implement respondToRequest() method.
    }

    public function getContext(): ContextInterface
    {
        // TODO: Implement getContext() method.
    }
}