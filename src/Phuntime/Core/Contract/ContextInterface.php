<?php
declare(strict_types=1);

namespace Phuntime\Core\Contract;

interface ContextInterface
{
    /**
     * @param string $key
     */
    public function getParameter(string $key): int|array|string;

    /**
     * Returns absolute path to function handler
     * @return string
     */
    public function getHandlerPath(): string;

    public function getFunctionDocumentRoot(): string;

    public function getHandlerScriptName(): string;

}