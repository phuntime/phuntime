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
        return $this->handle($event);

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

        $path = $request->getUri()->getPath();
        $documentRoot = $this->context->getFunctionDocumentRoot();
        $httpRequest = new HttpRequest();
        $httpRequest
            ->withDocumentRoot($documentRoot)
            ->withUri((string) $request->getUri())
            ->withRequestUri($request->getUri()->getPath())
            ->withQueryString($request->getUri()->getQuery())
            ->withScriptFilename($this->context->getHandlerPath())
            ->withMethod($request->getMethod())
            ->withBody((string)$request->getBody())
            ->withParam('HTTP_PROXY', '') // https://httpoxy.org/
            ->withParam('PATH_INFO', $path)
            ->withParam('PATH_TRANSLATED', sprintf('%s/%s', $documentRoot, $path));

        if($contentType !== null) {
            $this->logger->debug(sprintf('Setting content type to "%s".', $contentType));
            $httpRequest = $httpRequest->withContentType($contentType);
        }

        foreach ($request->getHeaders() as $psrHeaderKey => $headerValues) {
            $httpRequest = $httpRequest->withHeader($psrHeaderKey, reset($headerValues));
        }

        /**
         * FPM requests are handled by using Swoole Coroutine FastCGI Client
         * (@see https://www.swoole.co.uk/docs/modules/swoole-coroutine-fastcgi-client)
         * To make it work properly, we need to run our code in coroutine.
         */
        $fcgi = $this->fastCgiClient;
        $fcgiResponse = null;
        \Co\run(static function() use ($fcgi, $httpRequest, &$fcgiResponse) {
            $fcgiResponse = $fcgi->execute($httpRequest);
        });


        $this->logger->debug(sprintf('Response from FPM: HTTP %s, ', $fcgiResponse->getStatusCode()));
        $response = new Response();

        foreach ($fcgiResponse->getHeaders() as $headerName => $headerValue) {
            $response = $response->withHeader($headerName, $headerValue);
        }

        return $response
            ->withStatus($fcgiResponse->getStatusCode())
            ->withBody(
                Stream::create(
                    $fcgiResponse->getBody()
                )
            );
    }

}