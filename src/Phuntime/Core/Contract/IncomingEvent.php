<?php
declare(strict_types=1);

namespace Phuntime\Core\Contract;


class IncomingEvent
{

    protected array $payload;
    protected string $eventId;
    protected array $metadata;

    public function __construct(array $payload, string $eventId, array $metadata = [])
    {
        $this->payload = $payload;
        $this->eventId = $eventId;
        $this->metadata = $metadata;
    }

    /**
     * @return array
     */
    public function getPayload(): array
    {
        return $this->payload;
    }

    /**
     * @return string
     */
    public function getEventId(): string
    {
        return $this->eventId;
    }

    /**
     * @return array
     */
    public function getMetadata(): array
    {
        return $this->metadata;
    }

}