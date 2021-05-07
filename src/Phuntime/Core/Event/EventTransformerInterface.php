<?php
declare(strict_types=1);

namespace Phuntime\Core\Event;

interface EventTransformerInterface
{
    public function supports(array $payload): bool;

}