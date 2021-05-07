<?php
declare(strict_types=1);

namespace Phuntime\Core\EventTransformer;

/**
 * Used for processing asynchronous events that do not require any response to be set back to runtime.
 * (example: DynamoDB Stream Event)
 * @license MIT
 */
interface AsyncEventTransformerInterface extends EventTransformerInterface
{
    public function payloadToEvent(array $payload): object;
}