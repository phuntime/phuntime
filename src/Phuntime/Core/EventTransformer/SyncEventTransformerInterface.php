<?php
declare(strict_types=1);

namespace Phuntime\Core\EventTransformer;

/**
 * Used for handling Triggers and synchronous events
 * (examples: AWS Cognito Verify Auth Challenge)
 * @license MIT
 */
interface SyncEventTransformerInterface extends EventTransformerInterface
{
    /**
     * Processes input from runtime and turns them into its object-oriented version.
     * @param array $payload
     * @return object
     */
    public function payloadToEvent(array $payload): object;

    /**
     * Processes output from your function ans turns them into an array of things that would be sent back to runtime.
     * @param object $event
     * @return array
     */
    public function eventToPayload(object $event): array;
}