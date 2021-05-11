<?php
declare(strict_types=1);

namespace Phuntime\Aws;

/**
 * Knows how to talk with Lambda Runtime and brings them responses to his requests.
 * @license MIT
 */
class AwsRuntimeBridge
{
    protected string $runtimeHost;

    public function __construct(
        string $runtimeHost
    )
    {
        $this->runtimeHost = $runtimeHost;
    }

}