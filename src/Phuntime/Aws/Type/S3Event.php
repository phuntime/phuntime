<?php
declare(strict_types=1);

namespace Phuntime\Aws\Type;


use Phuntime\Core\Contract\EventInterface;

class S3Event implements EventInterface
{

    public function isAsync(): bool
    {
        return false;
    }
}