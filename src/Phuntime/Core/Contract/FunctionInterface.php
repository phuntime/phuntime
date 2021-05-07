<?php
declare(strict_types=1);

namespace Phuntime\Core\Contract;

use Phuntime\Core\Event\EventInterface;

interface FunctionInterface
{
    /**
     * Warm your app here.
     */
    public function init(): void;

    /**
     * Handle requests/events here.
     * @param EventInterface $event
     * @return void|object
     */
    public function handle(EventInterface $event);
}