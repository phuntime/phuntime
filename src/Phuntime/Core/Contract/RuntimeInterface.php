<?php
declare(strict_types=1);

namespace Phuntime\Core\Contract;

use Psr\Log\LoggerInterface;

/**
 * @author pizzaminded <mikolajczajkowsky@gmail.com>
 * @license MIT
 */
interface RuntimeInterface
{

    public function getContext(): ContextInterface;

    public function getLogger(): LoggerInterface;

    public function getNextEvent(): IncomingEvent;

    public function respondToEvent(string $eventId, object $response): void;

    public function handleInvocationError(\Throwable $exception, ?string $requestId = null): void;

    public function handleInitializationException(\Throwable $throwable);

}