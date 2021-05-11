<?php
declare(strict_types=1);

namespace Phuntime\Aws;

use PHPUnit\Framework\TestCase;

class AwsContextTest extends TestCase
{

    public function testConstructorWillBreakWhenNoRuntimeApiPassed()
    {
        self::expectException(\RuntimeException::class);
        self::expectDeprecationMessage('Missing AWS_LAMBDA_RUNTIME_API env!');

        AwsContext::fromArray([]);
    }
}