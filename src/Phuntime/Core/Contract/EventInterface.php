<?php
declare(strict_types=1);

namespace Phuntime\Core\Contract;

interface EventInterface
{
    public function isAsync(): bool;
}