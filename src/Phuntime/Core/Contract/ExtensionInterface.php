<?php
declare(strict_types=1);

namespace Phuntime\Core\Contract;


use Phuntime\Core\EventTransformer\EventTransformerInterface;

interface ExtensionInterface
{

    /**
     * @return EventTransformerInterface[]
     */
    public function registerEventTransformers(): array;

}