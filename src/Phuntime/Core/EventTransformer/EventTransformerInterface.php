<?php
declare(strict_types=1);

namespace Phuntime\Core\EventTransformer;

interface EventTransformerInterface
{
    public function supports(array $payload): bool;

}