<?php
declare(strict_types=1);

namespace Phuntime\Core;


use Phuntime\Core\FunctionHandler\FunctionInterface;

interface ContextInterface
{
    /**
     * @psalm-return \Phuntime\Core\FunctionHandler\Psr7FunctionInterface|\Phuntime\Core\FunctionHandler\HttpFoundationFunctionInterface
     */
    public function getFunction(): FunctionInterface;

}