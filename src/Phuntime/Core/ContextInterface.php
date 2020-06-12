<?php
declare(strict_types=1);

namespace Phuntime\Core;


use Phuntime\Core\FunctionHandler\FunctionInterface;

interface ContextInterface
{
    public function getFunction(): FunctionInterface;

}