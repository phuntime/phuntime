<?php
declare(strict_types=1);

namespace Phuntime;


class UnitTestHelper
{
    public static function getJsonFixture(string $fixtureName): array
    {
        return json_decode(
            file_get_contents(
                __DIR__ . '/../fixtures/' . $fixtureName . '.json'),
            true
        );
    }
}