<?php
declare(strict_types=1);

namespace Phuntime\Core\FunctionHandler;


use Closure;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Log\LoggerInterface;

/**
 * @author pizzaminded <mikolajczajkowsky@gmail.com>
 */
class WrappedClosureHandler implements FunctionInterface
{

    /**
     * @var Closure
     */
    protected Closure $function;

    /**
     * WrappedClosureHandler constructor.
     * @param Closure $function
     */
    public function __construct(Closure $function)
    {
        $this->function = $function;
    }

    /**
     * @codeCoverageIgnore
     * @inheritDoc
     */
    public function handleEvent(object $event)
    {
        return;
    }

    /**
     * Nothing to boot in this case
     * @codeCoverageIgnore
     */
    public function boot()
    {
        return;
    }

    /**
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $function = $this->function;
        return $function($request);
    }

    /**
     * @inheritDoc
     * @codeCoverageIgnore
     */
    public function setLogger(LoggerInterface $logger)
    {
        return;
    }
}