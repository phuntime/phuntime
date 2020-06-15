<?php
declare(strict_types=1);

namespace Phuntime\Local;


use Nyholm\Psr7\Factory\Psr17Factory;
use Phuntime\Core\ContextInterface;
use Phuntime\Core\RuntimeInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bridge\PsrHttpMessage\Factory\HttpFoundationFactory;
use Symfony\Bridge\PsrHttpMessage\Factory\PsrHttpFactory;

/**
 * @author pizzaminded <mikolajczajkowsky@gmail.com>
 * @license MIT
 */
class LocalRuntime implements RuntimeInterface
{
    protected LocalContext $context;
    protected LocalLogger $logger;

    public static function create(): self
    {
        $self = new self();
        $self->context = new LocalContext();
        $self->logger = new LocalLogger();
        return $self;
    }

    /**
     * @inheritDoc
     */
    public function getContext(): ContextInterface
    {
        return $this->context;
    }

    /**
     * @inheritDoc
     */
    public function getLogger(): LoggerInterface
    {
        return $this->logger;
    }

    /**
     * @inheritDoc
     */
    public function getNextRequest(): object
    {
        $psr17Factory = new Psr17Factory();
        $psrHttpFactory = new PsrHttpFactory(
            $psr17Factory,
            $psr17Factory,
            $psr17Factory,
            $psr17Factory
        );

        return $psrHttpFactory->createRequest(\Symfony\Component\HttpFoundation\Request::createFromGlobals());
    }

    /**
     * @inheritDoc
     */
    public function respondToRequest(string $requestId, ResponseInterface $response): void
    {
        (new HttpFoundationFactory())->createResponse($response)->send();
    }

    /**
     * @inheritDoc
     */
    public function handleInvocationError(\Throwable $exception, ?string $requestId = null): void
    {
        throw $exception;
    }

    public function handleInitializationException(\Throwable $throwable)
    {
        throw $throwable;
    }
}