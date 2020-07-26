<?php
declare(strict_types=1);

namespace Phuntime\Core;


use Phuntime\Core\FunctionHandler\FunctionInterface;

interface ContextInterface
{
    /**
     * @deprecated - this should be a member of RuntimeConfiguration class. Context should be used for platform-dependent thing.
     * @psalm-return \Phuntime\Core\FunctionHandler\Psr7FunctionInterface
     */
    public function getFunction(): FunctionInterface;

    /**
     * @param string $key
     * @return string|int|array
     */
    public function getParameter(string $key);

    /**
     * Returns absolute path to function handler
     * @return string
     */
    public function getHandlerPath(): string;
}