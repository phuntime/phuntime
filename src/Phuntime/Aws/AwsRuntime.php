<?php
declare(strict_types=1);

namespace Phuntime\Aws;


use Phuntime\Core\ContextInterface;
use Phuntime\Core\RuntimeInterface;
use Psr\Http\Message\ResponseInterface;
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
     * @var EventClassifier
     */
    protected EventClassifier $classifier;

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
        [$contentBody, $headers] = $this->request('GET', 'invocation/next');
        $content = json_decode($contentBody, true);

        if ($this->classifier->isApiGatewayProxyEvent($content)) {
            //This is the only place we have headers from Lambda runtime, so we need to add request id here
            $requestId = $headers['lambda-runtime-aws-request-id'];
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
            'multiValueHeaders' => $response->getHeaders(),
            'body' => (string)$response->getBody()
        ];

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
     * @param string $requestId
     * @param ErrorMessage $errorMessage
     * @param array $stackTrace
     */
    public function handleInvocationError(\Throwable $exception, ?string $requestId = null): void
    {

        if ($requestId !== null) {
            $output = [
                'errorMessage' => sprintf('InvocationError Occured: "%s", see CloudWatch logs for details.', $exception->getMessage()),
                'errorType' => get_class($exception)
            ];

            $output = json_encode($output);
            $this->request('POST', 'invocation/' . $requestId . '/error', $output, 'application/json');
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
            'errorMessage' => sprintf('InitializationException Occured: "%s", see CloudWatch logs for details.', $throwable->getMessage()),
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
     * Creates a new instance of AwsRuntime with all configuration taken from ennvironment variables
     * @return static
     */
    public static function fromEnvironment(): self
    {
        $self = new self();
        $self->context = AwsContext::fromArray($_ENV);
        $self->logger = new AwsLogger();
        $self->classifier = new EventClassifier();

        return $self;
    }

    protected function request(string $method, string $path, ?string $body = null, string $contentType = 'text/plain'): array
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

        $headers = [];
        curl_setopt($ch, CURLOPT_HEADERFUNCTION,
            function ($curl, $header) use (&$headers) {
                $len = strlen($header);
                $explodedHeader = explode(':', $header, 2);
                if (count($explodedHeader) !== 2) {
                    return $len;
                }
                list($name, $value) = $explodedHeader;
                if (empty($value)) {
                    return $len;
                }

                $name = strtolower(trim($name));
                if (array_key_exists($name, $headers) === false) {
                    $headers[$name] = trim($value);
                } else {
                    if (!is_array($headers[$name])) {
                        $headers[$name] = array($headers[$name]);
                    }
                    $headers[$name][] = trim($value);
                }

                return $len;

            }
        );

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

        return [$result, $headers];
    }
}