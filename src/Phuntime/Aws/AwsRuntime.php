<?php
declare(strict_types=1);

namespace Phuntime\Aws;

use Phuntime\Aws\Type\ApiGatewayProxyEvent;
use Phuntime\Aws\Type\ApiGatewayProxyResult;
use Phuntime\Core\Contract\ContextInterface;
use Phuntime\Core\Contract\RuntimeInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Log\LoggerInterface;
use RuntimeException;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface as HttpClientResponseInterface;

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

    /**
     * @var EventClassifier
     */
    protected EventClassifier $classifier;

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
     * @return object
     * @throws TransportExceptionInterface
     */
    public function getNextEvent(): object
    {
        list($content,) = $this->runtimeClient->getEvent();

        if ($this->classifier->isApiGatewayV1ProxyEvent($content)) {
            return ApiGatewayProxyEvent::fromArray($content);
        }

        throw new RuntimeException('Unsupported event received');
    }

    /**
     * @param string $eventId
     * @param object $response
     * @throws TransportExceptionInterface
     */
    public function respondToEvent(string $eventId, object $response): void
    {
        if(!($response instanceof ApiGatewayProxyResult)) {
            throw new \InvalidArgumentException(sprintf('Unsupported event passed to %s', __METHOD__));
        }

        $this->runtimeClient->respondToEvent($eventId, $response->toArray());
    }


    /**
     * Emits error occured during event handling
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
                'InvocationError occured during request execution: %s ',
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
        $self->classifier = new EventClassifier();

        return $self;
    }

    public function getContext(): ContextInterface
    {
        return $this->context;
    }
}