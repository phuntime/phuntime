<?php
declare(strict_types=1);

namespace Phuntime\Core;


use Phuntime\Core\Exception\InitializationException;
use Phuntime\Core\FunctionHandler\FunctionInterface;
use Phuntime\Core\FunctionHandler\WrappedClosureHandler;
use Psr\Http\Message\RequestInterface;

/**
 * Exposes some features that probably would be repeated in any ContextInterface
 * @author pizzaminded <mikolajczajkowsky@gmail.com>
 * @license MIT
 * @deprecated - drop it, or refactor
 */
trait ContextTrait
{

    /**
     * @param string $taskRoot
     * @param string $handlerPath
     * @return FunctionInterface
     */
    protected function locateFunction(string $taskRoot, string $handlerPath)
    {

        $functionDefinitionFiles = [
            $handlerPath,
            '.phuntime.php'
        ];

        foreach ($functionDefinitionFiles as $definitionFile) {
            $pathToInclude = sprintf('%s/%s', $taskRoot, $definitionFile);

            if (file_exists($pathToInclude)) {
                /**  @noinspection PhpIncludeInspection */
                $functionDefinition = include_once $pathToInclude;

                if (!is_object($functionDefinition)) {
                    throw  InitializationException::invalidFunctionPassed($functionDefinition, $pathToInclude);
                }

                if ($this->isCallbackBasedFunction($functionDefinition)) {
                    return new WrappedClosureHandler($functionDefinition);
                }

                if ($functionDefinition instanceof FunctionInterface) {
                    return $functionDefinition;
                }

                throw InitializationException::invalidFunctionPassed($functionDefinition, $pathToInclude);
            }
        }

        throw new \RuntimeException(sprintf('Could not find any function definition file! Tried %s', json_encode($functionDefinitionFiles)));
    }

    /**
     * @param object $functionDefinition
     * @return bool
     */
    protected function isCallbackBasedFunction(object $functionDefinition)
    {
        if (!($functionDefinition instanceof \Closure)) {
            return false;
        }

        $reflection = new \ReflectionFunction($functionDefinition);
        $parameters = $reflection->getParameters();

        if (count($parameters) === 0) {
            return false;
        }

        $firstParameter = $parameters[0];
        $firstParameterType = @(string)$firstParameter->getType();

        if (in_array(RequestInterface::class, class_implements($firstParameterType))) {
            return true;
        }

        return false;
    }
}