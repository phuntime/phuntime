<?php
declare(strict_types=1);

namespace Phuntime\Core\Event;

interface EventInterface
{
    public function isAsync(): bool;
}