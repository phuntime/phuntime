<?php
declare(strict_types=1);

namespace Phuntime\Core\Contract;


interface SyncEventInterface extends EventInterface
{
    public function getEventId(): string;
}