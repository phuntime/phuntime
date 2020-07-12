<?php
declare(strict_types=1);

namespace Phuntime\Core\FunctionHandler;


use Phuntime\Core\PhpFpmProcess;
use Phuntime\Core\RuntimeConfiguration;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Log\LoggerInterface;
use Swoole\Coroutine\FastCGI\Client;
use Swoole\FastCGI\HttpRequest;

/**
 * Creates a PHP-FPM process and forwards them all requests.
 * @package Phuntime\Core\FunctionHandler
 * @license MIT
 */
class FpmHandler implements FunctionInterface
{
    /**
     * @var PhpFpmProcess
     */
    protected PhpFpmProcess $process;

    /**
     * @var Client
     */
    protected Client $fastCgiClient;

    /**
     * FpmHandler constructor.
     */
    public function __construct()
    {
        $this->process = new PhpFpmProcess();
        $this->fastCgiClient = new Client(
            '127.0.0.1',
            PhpFpmProcess::LISTEN_PORT
        );
    }

    public function handleEvent(object $event)
    {

    }

    public function boot()
    {
        // TODO: Implement boot() method.
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $this->process->start();
        $request = new HttpRequest();

    }

    public function setLogger(LoggerInterface $logger)
    {
        // TODO: Implement setLogger() method.
    }

    public function getRuntimeConfiguration(): RuntimeConfiguration
    {
        // TODO: Implement getRuntimeConfiguration() method.
    }
}