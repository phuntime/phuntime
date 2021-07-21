<?php
declare(strict_types=1);

namespace Phuntime\Core\Contract;

interface ContextInterface
{
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