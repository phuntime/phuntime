<?php
declare(strict_types=1);

namespace Phuntime\Core\Event;

interface EventHandlerInterface
{
    public function handle(object $event): ?array;
}