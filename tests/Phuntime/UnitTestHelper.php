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
        $path = __DIR__ . '/../fixtures/' . $fixtureName . '.json';

        if(!file_exists($path)) {
            throw new \LogicException(sprintf('json fixture "%s" not found.', $fixtureName));
        }

        return json_decode(
            file_get_contents($path),
            true,
            512,
            JSON_THROW_ON_ERROR
        );
    }
}