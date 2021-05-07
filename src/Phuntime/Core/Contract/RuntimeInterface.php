<?php
declare(strict_types=1);

namespace Phuntime\Core\Contract;

use Psr\Http\Message\ResponseInterface;
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
     * @return object
     */
    public function getNextRequest(): object;

    /**
     * @deprecated
     * @param string $requestId
     * @param ResponseInterface $response
     * @return void
     */
    public function respondToRequest(string $requestId, ResponseInterface $response): void;

    /**
     * @param \Throwable $exception
     * @param string|null $requestId - may be null as not in any event RequestId is present
     */
    public function handleInvocationError(\Throwable $exception, ?string $requestId = null): void;


    public function handleInitializationException(\Throwable $throwable);

}