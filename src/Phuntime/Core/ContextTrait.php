<?php
declare(strict_types=1);

namespace Phuntime\Core;


use Phuntime\Core\FunctionHandler\FunctionInterface;

trait ContextTrait
{

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

                if ($functionDefinition instanceof FunctionInterface) {
                    return $functionDefinition;
                }

                throw new \RuntimeException(
                    sprintf(
                        'File %s should return instanceof FunctionInterface, %s returned',
                        $pathToInclude,
                        gettype($functionDefinition)
                    )
                );
            }
        }

        throw new \RuntimeException(sprintf('Could not find any function definition file! Tried %s', json_encode($functionDefinitionFiles)));
    }
}