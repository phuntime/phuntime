<?php
declare(strict_types=1);

namespace Phuntime\Fpm;


use Nyholm\Psr7\Response;
use Nyholm\Psr7\Stream;
use Phuntime\Core\Contract\ContextInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Log\LoggerInterface;
use Swoole\Coroutine\FastCGI\Client;
use Swoole\FastCGI\HttpRequest;

/**
 * @license MIT
 */
class FpmHandler
{
    protected Client $fastCgiClient;

    protected ContextInterface $context;

    protected LoggerInterface $logger;

    public function __construct(ContextInterface $context, LoggerInterface $logger)
    {
        $this->context = $context;
        $this->fastCgiClient = new Client(
            '127.0.0.1',
            PhpFpmProcess::LISTEN_PORT
        );

        $this->logger = $logger;
    }

    public function handleEvent(object $event)
    {


    }

    public function boot()
    {
        // TODO: Implement boot() method.
    }

    public function handle(RequestInterface $request): ResponseInterface
    {
        $contentTypeHeaders = $request->getHeader('Content-Type');
        if(count($contentTypeHeaders) === 0) {
            $contentType = null;
        } else {
            $contentType = reset($contentTypeHeaders);
        }

        $httpRequest = new HttpRequest();
        $httpRequest
            ->withRequestUri($request->getUri()->getPath())
            //->withQueryString(http_build_query($request->getQueryParams()))
            //->withScriptFilename($this->context->getHandlerPath())
            ->withMethod($request->getMethod())
            ->withBody((string)$request->getBody());

        if($contentType !== null) {
            $this->logger->debug(sprintf('Setting content type to "%s".', $contentType));
            $httpRequest = $httpRequest->withContentType($contentType);
        }

        foreach ($request->getHeaders() as $psrHeaderKey => $headerValues) {
            $httpRequest = $httpRequest->withHeader($psrHeaderKey, reset($headerValues));
        }


        $fpmResponse = $this->fastCgiClient->execute($httpRequest);
        $this->logger->debug(sprintf('Response from FPM: HTTP %s, ', $fpmResponse->getStatusCode()));
        $response = new Response();

        foreach ($fpmResponse->getHeaders() as $headerName => $headerValue) {
            $response = $response->withHeader($headerName, $headerValue);
        }

        return $response
            ->withStatus($fpmResponse->getStatusCode())
            ->withBody(
                Stream::create(
                    $fpmResponse->getBody()
                )
            );
    }

    public function getRuntimeConfiguration(): RuntimeConfiguration
    {
        // TODO: Implement getRuntimeConfiguration() method.
    }
}