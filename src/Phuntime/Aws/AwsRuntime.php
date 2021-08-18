<?php
declare(strict_types=1);

namespace Phuntime\Aws;

use Phuntime\Aws\Type\ApiGatewayProxyEvent;
use Phuntime\Aws\Type\ApiGatewayProxyResult;
use Phuntime\Core\Contract\ContextInterface;
use Phuntime\Core\Contract\IncomingEvent;
use Phuntime\Core\Contract\RuntimeInterface;
use Psr\Log\LoggerInterface;
use RuntimeException;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

/**
 * @see https://docs.aws.amazon.com/lambda/latest/dg/runtimes-api.html
 * @author pizzaminded <mikolajczajkowsky@gmail.com>
 * @license MIT
 */
class AwsRuntime implements RuntimeInterface
{
    /**
     * @var AwsContext
     */
    protected AwsContext $context;

    /**
     * @var AwsLogger
     */
    protected AwsLogger $logger;

    protected AwsRuntimeClient $runtimeClient;

    public function __construct(
        AwsContext $context,
        ?AwsRuntimeClient $runtimeClient = null
    )
    {
        $this->context = $context;
        $this->runtimeClient = $runtimeClient ?? new AwsRuntimeClient($context->getRuntimeHost());
    }

    /**
     * @return LoggerInterface
     */
    public function getLogger(): LoggerInterface
    {
        return $this->logger;
    }

    /**
     * @return IncomingEvent
     * @throws TransportExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface
     */
    public function getNextEvent(): IncomingEvent
    {
        $this->logger->debug('next event requested');
        [$content, $headers, $requestId] = $this->runtimeClient->getEvent();
        /** @var $content array */
        /** @var $headers array */
        /** @var $requestId string */
        return new IncomingEvent($content, $requestId, $headers);
    }

    /**
     * @param string $eventId
     * @param object $response
     * @throws TransportExceptionInterface
     */
    public function respondToEvent(string $eventId, object $response): void
    {
        if (!($response instanceof ApiGatewayProxyResult)) {
            throw new \InvalidArgumentException(sprintf('Unsupported event passed to %s', __METHOD__));
        }

        $this->runtimeClient->respondToEvent($eventId, $response->toArray());
    }


    /**
     * Emits error occurred during event handling
     * @param \Throwable $exception
     * @param string|null $requestId
     * @throws TransportExceptionInterface
     */
    public function handleInvocationError(\Throwable $exception, ?string $requestId = null): void
    {

        if ($requestId !== null) {
            $this->runtimeClient->handleInvocationError($exception, $requestId);
        }

        $this->getLogger()->critical(
            sprintf(
                'InvocationError occurred during request execution: %s ',
                $exception->getMessage()
            ),
            $exception->getTrace()
        );
    }

    /**
     * @param \Throwable $throwable
     * @throws TransportExceptionInterface
     */
    public function handleInitializationException(\Throwable $throwable)
    {
        $this->runtimeClient->handleInitializationException($throwable);

        //Also send to stderr
        $this->getLogger()->emergency(
            sprintf(
                'InitializationException: %s (%s)',
                $throwable->getMessage(),
                get_class($throwable)
            ),
            $throwable->getTrace()
        );
    }

    /**
     * Creates a new instance of AwsRuntime with all configuration taken from environment variables
     * @param array $env - inject $_ENV here
     * @return static
     */
    public static function fromEnvironment(array $env): self
    {
        $self = new self(
            AwsContext::fromArray($env)
        );
        $self->logger = new AwsLogger();

        return $self;
    }

    public function getContext(): ContextInterface
    {
        return $this->context;
    }
}