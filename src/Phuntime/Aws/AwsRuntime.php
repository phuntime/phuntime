<?php
declare(strict_types=1);

namespace Phuntime\Aws;


use Phuntime\Core\ContextInterface;
use Phuntime\Core\RuntimeInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpClient\HttpClient;
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
     */
    public function getNextRequest(): object
    {
        $requestData = $this->request('GET', 'invocation/next');
        $content = $requestData->toArray(false);
        $headers = $requestData->getHeaders(false);

        if ($this->classifier->isApiGatewayProxyEvent($content)) {
            //This is the only place we have headers from Lambda runtime, so we need to add request id here
            $requestId = $headers['lambda-runtime-aws-request-id'][0];
            return RequestBuilder::buildPsr7Request($content)
                ->withAttribute('REQUEST_ID', $requestId);
        }

        throw new \RuntimeException('Unsupported event received');
    }

    /**
     * All HTTP Responses must be converted to API Gateway Proxy Result
     * @see https://docs.aws.amazon.com/apigateway/latest/developerguide/set-up-lambda-proxy-integrations.html#api-gateway-simple-proxy-for-lambda-output-format
     * @param string $requestId
     * @param ResponseInterface $response
     */
    public function respondToRequest(string $requestId, ResponseInterface $response): void
    {
        $proxyResult = [
            'statusCode' => $response->getStatusCode(),
            'body' => (string)$response->getBody()
        ];

        $headers = $response->getHeaders();

        //API Gateway expects to receive a array<string, array|string> or "empty" JSON object in this field
        //Does not like empty arrays passed here and it will throw "Malformed Lambda proxy response" error
        if(count($headers) > 0) {
            $proxyResult['multiValueHeaders'] = $headers;
        }

        $this->request('POST', 'invocation/' . $requestId . '/response', json_encode($proxyResult), 'application/json');
    }

    /**
     * @return ContextInterface
     */
    public function getContext(): ContextInterface
    {
        return $this->context;
    }


    /**
     * Emits error occured during event handling
     * @param \Throwable $exception
     * @param string|null $requestId
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
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
     */
    public function handleInitializationException(\Throwable $throwable)
    {
        $output = [
            'errorMessage' => sprintf('InitializationException Occured: "%s"', $throwable->getMessage()),
            'errorType' => get_class($throwable)
        ];

        $output = json_encode($output);
        $this->request('POST', 'init/error', $output, 'application/json');

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
}