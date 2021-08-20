<?php
declare(strict_types=1);

namespace Phuntime;

use function json_decode;

class UnitTestHelper
{
    /**
     * @throws \JsonException
     */
    public static function getJsonFixture(string $fixtureName): array
    {
        return json_decode(
            file_get_contents(__DIR__ . '/../fixtures/' . $fixtureName . '.json'),
            true,
            512,
            JSON_THROW_ON_ERROR
        );
    }
}