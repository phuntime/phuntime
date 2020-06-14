<?php
declare(strict_types=1);

namespace Phuntime\App;


use Phuntime\Core\FunctionHandler\HttpFoundationFunctionInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @author pizzaminded <mikolajczajkowsky@gmail.com>
 */
class MyFunction implements HttpFoundationFunctionInterface
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

    public function handle(Request $request): Response
    {
        return new Response('this is a test');
    }

    /**
     * @inheritDoc
     */
    public function setLogger(LoggerInterface $logger)
    {
        // TODO: Implement setLogger() method.
    }
}