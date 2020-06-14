<?php
declare(strict_types=1);

namespace Phuntime\Core;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Log\LoggerInterface;

/**
 * @author pizzaminded <mikolajczajkowsky@gmail.com>
 * @license MIT
 */
interface RuntimeInterface
{

    /**
     * @return ContextInterface
     */
    public function getContext(): ContextInterface;

    /**
     * Exposes a runtime-specific logger instance.
     * @return LoggerInterface
     */
    public function getLogger(): LoggerInterface;

    /**
     * Returns next request that has been received by server.
     * @return ServerRequestInterface
     */
    public function getNextRequest(): ServerRequestInterface;

    /**
     * @param string $requestId
     * @param ResponseInterface $response
     * @return void
     */
    public function respondToRequest(string $requestId, ResponseInterface $response): void;


    public function handleInitializationException(\Throwable $throwable);
}