<?php
declare(strict_types=1);

namespace Phuntime\Aws;


use Phuntime\Core\ContextInterface;
use Phuntime\Core\RuntimeInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Log\LoggerInterface;

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
     * @return LoggerInterface
     */
    public function getLogger(): LoggerInterface
    {
        return $this->logger;
    }

    public function getNextRequest(): object
    {
        // TODO: Implement getNextRequest() method.
    }

    public function respondToRequest(string $requestId, ResponseInterface $response): void
    {
        // TODO: Implement respondToRequest() method.
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
     * @param string $requestId
     * @param ErrorMessage $errorMessage
     * @param array $stackTrace
     */
    public function sendInvocationError(string $requestId, ErrorMessage $errorMessage, array $stackTrace = [])
    {

        $this->getLogger()->critical(
            sprintf(
                'Error occured during request #%s execution: %s (%s)',
                $requestId,
                $errorMessage->getErrorMessage(),
                $errorMessage->getErrorType()
            ),
            $stackTrace
        );
    }

    /**
     * @param \Throwable $throwable
     */
    public function handleInitializationException(\Throwable $throwable)
    {
        //Also send to stderr
        $this->getLogger()->emergency(
            sprintf(
                'Emergency Error: %s (%s)',
                $throwable->getMessage(),
                get_class($throwable)
            ),
        );
    }

    /**
     * Creates a new instance of AwsRuntime with all configuration taken from ennvironment variables
     * @return static
     */
    public static function fromEnvironment(): self
    {
        $self = new self();
        $self->context = AwsContext::fromArray($_ENV);
        $self->logger = new AwsLogger();

        return $self;
    }

    protected function request(string $method, string $path, ?string $body = null, string $contentType = 'text/plain'): string
    {
        //normalize HTTP Method
        $method = strtoupper($method);

        $url = sprintf(
            'http://%s/2018-06-01/runtime/%s',
            $this->context->getParameter('AWS_LAMBDA_RUNTIME_API'),
            $path
        );


        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        if ($method == 'POST') {
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Content-Length: ' . strlen($body),
                'Content-Type: ' . $contentType,
            ]);
        }

        $result = curl_exec($ch);
        $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        return $result;
    }
}