<?php
declare(strict_types=1);

namespace Phuntime\Local;


use Psr\Log\AbstractLogger;

class LocalLogger extends AbstractLogger
{


    /**
     * @inheritDoc
     */
    public function log($level, $message, array $context = array())
    {
        file_put_contents(
            'php://stderr',
            sprintf('[%s] %s ', $level, $message)
        );
    }
}