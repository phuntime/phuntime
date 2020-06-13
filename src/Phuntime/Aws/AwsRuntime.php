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


    /**
     * Emits error that occured during runtime initialization
     * @param ErrorMessage $errorMessage
     */
    public function sendInitializationError(ErrorMessage $errorMessage)
    {
        //Also send to stderr
        $this->getLogger()->emergency(
            sprintf(
                'Emergency Error: %s (%s)',
                $errorMessage->getErrorMessage(),
                $errorMessage->getErrorType()
            ),
        );
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
}