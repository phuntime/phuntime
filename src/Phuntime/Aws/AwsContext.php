<?php
declare(strict_types=1);

namespace Phuntime\Aws;

use Phuntime\Core\Contract\ContextInterface;

/**
 * @author pizzaminded <mikolajczajkowsky@gmail.com>
 */
class AwsContext implements ContextInterface
{
    /**
     * @var array<string, string>
     */
    protected array $parameters = [];

    protected function __construct() {}

    /**
     * @param array $parameters
     */
    public static function fromArray(array $parameters = []): self
    {
        if(!isset($parameters['AWS_LAMBDA_RUNTIME_API'])) {
            throw new \RuntimeException('Missing AWS_LAMBDA_RUNTIME_API env!');
        }

        $self = new self();
        $self->parameters = $parameters;

        return $self;
    }


    /**
     * @inheritDoc
     */
    public function getFunction(): FunctionInterface
    {
        return $this->locateFunction(
            $this->getParameter('LAMBDA_TASK_ROOT'),
            $this->getParameter('_HANDLER')
        );
    }



    /**
     * @return string
     */
    public function getHandlerPath(): string
    {
        return sprintf(
            '%s/%s',
            $this->getParameter('LAMBDA_TASK_ROOT'),
            $this->getParameter('_HANDLER')
        );
    }

    public function getRuntimeHost(): string
    {
        return $this->parameters['AWS_LAMBDA_RUNTIME_API'];
    }

    public function getFunctionDocumentRoot(): string
    {
        return (string)$this->getParameter('LAMBDA_TASK_ROOT');
    }

    public function getHandlerScriptName(): string
    {
        return $this->getParameter('_HANDLER');
    }

    public function getParameter(string $key): int|array|string
    {
        if (!isset($this->parameters[$key])) {
            throw new \RuntimeException(sprintf('Could not find "%s" parameter in context.', $key));
        }

        return $this->parameters[$key];
    }
}