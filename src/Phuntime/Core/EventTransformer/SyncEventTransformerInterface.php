<?php
declare(strict_types=1);

namespace Phuntime\Core\EventTransformer;

use Phuntime\Core\Contract\EventInterface;

/**
 * Used for handling Triggers and synchronous events
 * (examples: AWS Cognito Verify Auth Challenge)
 * @license MIT
 */
interface SyncEventTransformerInterface extends EventTransformerInterface
{
    /**
     * Processes output from your function ans turns them into an object that would be sent back to runtime.
     * @param object $event
     * @return EventInterface
     */
    public function transformToRuntime(object $event): EventInterface;
}