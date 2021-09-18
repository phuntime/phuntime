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
use Swoole\FastCGI\HttpResponse;

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

    public function boot(): void
    {
        // TODO: Implement boot() method.
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
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
            ->withRequestUri(sprintf('%s?%s', $request->getUri()->getPath(), $request->getUri()->getQuery()))
            ->withQueryString($request->getUri()->getQuery())
            ->withScriptFilename($this->context->getHandlerPath())
            ->withMethod($request->getMethod())
            ->withBody((string)$request->getBody())
            ->withParam('HTTP_PROXY', '') // https://httpoxy.org/
            ->withParam('PATH_INFO', $path)
            ->withParam('PATH_TRANSLATED', sprintf('%s/%s', rtrim($documentRoot, '/'), ltrim($this->context->getHandlerScriptName(), '/')))
            ->withParam('SCRIPT_NAME', $this->context->getHandlerScriptName());

        //@TODO: assert presence of handler file in PATH_TRANSLATED

        if($contentType !== null) {
            $this->logger->debug(sprintf('Setting content type to "%s".', $contentType));
            $httpRequest = $httpRequest->withContentType($contentType);
        }

        /** @psalm-var array<string, string[]> $psrHeaders */
        $psrHeaders = $request->getHeaders();
        foreach ($psrHeaders as $psrHeaderKey => $headerValues) {
            $httpRequest = $httpRequest->withHeader($psrHeaderKey, reset($headerValues));
        }

        $cookiePairs = [];
        foreach ($request->getCookieParams() as $cookieName => $cookieVal) {
            $cookiePairs[] = sprintf('%s=%s', $cookieName, $cookieVal);
        }

        if(count($cookiePairs) > 0) {
            $httpRequest = $httpRequest->withHeader('Cookie', implode('; ', $cookiePairs));
        }

        /**
         * FPM requests are handled by using Swoole Coroutine FastCGI Client
         * (@see https://www.swoole.co.uk/docs/modules/swoole-coroutine-fastcgi-client)
         * To make it work properly, we need to run our code in coroutine.
         */
        $fcgi = $this->fastCgiClient;
        $fcgiResponse = null;
        \Co\run(static function () use ($fcgi, $httpRequest, &$fcgiResponse) {
            $fcgiResponse = $fcgi->execute($httpRequest);
        });

        /** @var HttpResponse $fcgiResponse */


        $this->logger->debug(sprintf('Response from FPM: HTTP %s, ', $fcgiResponse->getStatusCode()));
        $response = new Response();

        /** @var array<string, string> $headers */
        $headers = $fcgiResponse->getHeaders();
        foreach ($headers as $headerName => $headerValue) {
            $response = $response->withHeader($headerName, $headerValue);
        }

        if (count($fcgiResponse->getSetCookieHeaderLines()) > 0) {
            $response = $response->withHeader('Set-Cookie', $fcgiResponse->getSetCookieHeaderLines());
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