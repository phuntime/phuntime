<?php
declare(strict_types=1);

namespace Phuntime\Aws;


use Phuntime\Core\ContextInterface;
use Phuntime\Core\ContextTrait;
use Phuntime\Core\FunctionHandler\FunctionInterface;

/**
 * @author pizzaminded <mikolajczajkowsky@gmail.com>
 */
class AwsContext implements ContextInterface
{

    use ContextTrait;

    /**
     * @var array<string, string|int|array>
     */
    protected array $parameters = [];

    /**
     * @param array $parameters
     * @return static
     */
    public static function fromArray(array $parameters = []): self
    {
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
     * @inheritDoc
     */
    public function getParameter(string $key)
    {
        if (!isset($this->parameters[$key])) {
            throw new \RuntimeException(sprintf('Could not find "%s" parameter in context.', $key));
        }

        return $this->parameters[$key];
    }
}