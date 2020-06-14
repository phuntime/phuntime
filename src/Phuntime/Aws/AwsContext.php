<?php
declare(strict_types=1);

namespace Phuntime\Aws;


use Phuntime\Core\ContextInterface;
use Phuntime\Core\FunctionHandler\FunctionInterface;

/**
 * @author pizzaminded <mikolajczajkowsky@gmail.com>
 */
class AwsContext implements ContextInterface
{

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
        $taskRoot = $this->getParameter('LAMBDA_TASK_ROOT');

        $functionDefinitionFiles = [
            $this->getParameter('_HANDLER'),
            '.phuntime.php'
        ];

        foreach ($functionDefinitionFiles as $definitionFile) {
            $pathToInclude = sprintf('%s/%s', $taskRoot, $definitionFile);

            if (file_exists($pathToInclude)) {
                /**  @noinspection PhpIncludeInspection */
                $functionDefinition = include_once $pathToInclude;

                if ($functionDefinition instanceof FunctionInterface) {
                    return $functionDefinition;
                }

                throw new \RuntimeException('File %s should return instanceof FunctionInterface, %s returned', $pathToInclude, gettype($functionDefinition));
            }
        }

        throw new \RuntimeException(sprintf('Could not find any function definition file! Tried %s', json_encode($functionDefinitionFiles)));
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