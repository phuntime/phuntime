<?php
declare(strict_types=1);

namespace Phuntime\Local;

use Phuntime\Core\ContextInterface;
use Phuntime\Core\ContextTrait;
use Phuntime\Core\FunctionHandler\FunctionInterface;

class LocalContext implements ContextInterface
{

    use ContextTrait;

    /**
     * @inheritDoc
     */
    public function getFunction(): FunctionInterface
    {
        return $this->locateFunction(
            getcwd(),
            '.phuntime.local.php'
        );
    }

    /**
     * @inheritDoc
     */
    public function getParameter(string $key)
    {
        throw new \RuntimeException('Not implemented Yet');
    }
}