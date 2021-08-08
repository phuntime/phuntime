<?php
declare(strict_types=1);

namespace Phuntime\Core\EventTransformer;

use Phuntime\Core\Contract\EventInterface;

interface EventTransformerInterface
{
    /**
     * Processes object received from runtime and transforms them to some other
     * @param EventInterface $object $object
     * @return object
     */
    public function transformFromRuntime(EventInterface $object): object;

    /**
     * @param object $from received from runtime
     * @return bool
     */
    public function supports(object $from): bool;

}