<?php
declare(strict_types=1);

namespace Phuntime\Core;

use Phuntime\Core\FunctionHandler\FunctionInterface;
use Psr\Http\Message\ServerRequestInterface;
use Throwable;

/**
 * Acts as a bridge between runtime and your application.
 * @author pizzaminded <mikolajczajkowsky@gmail.com>
 * @license MIT
 */
class EventProcessor
{

    /**
     * @var string
     */
    public const VERSION = '0.0.2';

    /**
     * @var RuntimeInterface
     */
    protected RuntimeInterface $runtime;

    /**
     * @var ContextInterface
     */
    protected ContextInterface $context;

    /**
     * @var FunctionInterface
     */
    protected ?FunctionInterface $function = null;


    /**
     * @param RuntimeInterface $runtime
     * @param ContextInterface $context
     * @param FunctionInterface|null $function
     */
    protected function __construct(
        RuntimeInterface $runtime,
        ContextInterface $context,
        ?FunctionInterface $function = null
    )
    {
        $this->runtime = $runtime;
        $this->context = $context;
        $this->function = $function;
    }

    /**
     * @param RuntimeInterface $runtime
     * @param FunctionInterface|null $function
     * @return self
     */
    public static function fromRuntime(RuntimeInterface $runtime, ?FunctionInterface $function = null): self
    {
        return new self(
            $runtime,
            $runtime->getContext(),
            $function
        );
    }


    /**
     * @throws Throwable
     */
    public function boot()
    {
        if ($this->function === null) {
            $this->function = $this->context->getFunction();
            $this->runtime->getLogger()->debug('A function ' . get_class($this->function) . ' has been passed by project to handler.');
        }

        //warm up your application
        $this->function->boot();
        $this->runtime->getLogger()->debug('Phuntime is up & running & waiting for events');
    }


    /**
     * @param object $event
     */
    public function handleEvent(object $event)
    {
        if ($event instanceof ServerRequestInterface) {
            $this->handleHttpEvent($event);
            return;
        }

        throw new \RuntimeException('Unsupported event given');
    }

    /**
     * Handles HTTP Requests
     * @param ServerRequestInterface $request
     */
    protected function handleHttpEvent(ServerRequestInterface $request)
    {
        $requestId = $request->getAttribute('REQUEST_ID');
        try {
            $response = $this->function->handle($request);
            $this->runtime->respondToRequest($requestId, $response);
        } catch (\Throwable $exception) {
            $this->runtime->handleInvocationError($exception, $requestId);
        }
    }
}