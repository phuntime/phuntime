<?php
declare(strict_types=1);

namespace Phuntime\Core;

use Nyholm\Psr7\Factory\Psr17Factory;
use Phuntime\Core\FunctionHandler\FunctionInterface;
use Phuntime\Core\FunctionHandler\HttpFoundationFunctionInterface;
use Phuntime\Core\FunctionHandler\Psr7FunctionInterface;
use Symfony\Bridge\PsrHttpMessage\Factory\HttpFoundationFactory;
use Symfony\Bridge\PsrHttpMessage\Factory\PsrHttpFactory;
use Symfony\Bridge\PsrHttpMessage\HttpFoundationFactoryInterface;
use Symfony\Bridge\PsrHttpMessage\HttpMessageFactoryInterface;
use Throwable;

/**
 * Acts as a bridge between runtime and your application.
 * @author pizzaminded <mikolajczajkowsky@gmail.com>
 * @license MIT
 */
class Handler
{

    /**
     * @var string
     */
    public const VERSION = '0.0.1';

    /**
     * @var RuntimeInterface
     */
    protected RuntimeInterface $runtime;

    /**
     * @var ContextInterface
     */
    protected ContextInterface $context;

    /**
     * @var Psr7FunctionInterface|HttpFoundationFunctionInterface|null
     */
    protected $function = null;

    /**
     * @var HttpFoundationFactoryInterface|null
     */
    protected ?HttpFoundationFactoryInterface $httpFoundationFactory = null;

    /**
     * @var HttpMessageFactoryInterface|null
     */
    protected ?HttpMessageFactoryInterface $psrHttpFactory = null;

    /**
     * @param RuntimeInterface $runtime
     * @param ContextInterface $context
     * @psalm-param Psr7FunctionInterface|HttpFoundationFunctionInterface|null $function
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
     * @return self
     */
    public static function fromRuntime(RuntimeInterface $runtime): self
    {
        return new self(
            $runtime,
            $runtime->getContext(),
            null
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

    protected function getHttpFoundationFactory(): HttpFoundationFactoryInterface
    {
        if ($this->httpFoundationFactory === null) {
            $this->httpFoundationFactory = new HttpFoundationFactory();
        }

        return $this->httpFoundationFactory;
    }

    protected function getHttpMessageFactory(): HttpMessageFactoryInterface
    {
        if ($this->psrHttpFactory === null) {
            $psr17Factory = new Psr17Factory();

            $this->psrHttpFactory = new PsrHttpFactory(
                $psr17Factory,
                $psr17Factory,
                $psr17Factory,
                $psr17Factory
            );
        }

        return $this->psrHttpFactory;
    }

    /**
     * @param object $event
     */
    public function handleEvent(object $event)
    {

    }
}