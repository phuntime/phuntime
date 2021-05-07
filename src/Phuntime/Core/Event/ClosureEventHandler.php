<?php
declare(strict_types=1);

namespace Phuntime\Core\Event;


class ClosureEventHandler implements EventHandlerInterface
{
    /**
     * @var EventTransformerInterface[]
     */
    protected array $eventTransformers = [];

    public function handle(object $event): ?array
    {
    }
}