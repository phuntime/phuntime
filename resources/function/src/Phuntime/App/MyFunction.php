<?php
declare(strict_types=1);

namespace Phuntime\App;


use Phuntime\Core\FunctionHandler\FunctionInterface;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @author pizzaminded <mikolajczajkowsky@gmail.com>
 */
class MyFunction implements FunctionInterface
{

    /**
     * @inheritDoc
     */
    public function handleEvent(object $event)
    {
        // TODO: Implement handleEvent() method.
    }

    public function boot()
    {
        // TODO: Implement boot() method.
    }


    /**
     * @inheritDoc
     */
    public function setLogger(LoggerInterface $logger)
    {
        // TODO: Implement setLogger() method.
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
       return new \Nyholm\Psr7\Response(200, [], 'im working!');
    }
}