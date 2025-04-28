<?php

declare(strict_types=1);

namespace Goetas\JmsSerializerPhpstanExtension;

use PHPStan\Testing\TypeInferenceTestCase;
use PHPUnit\Framework\Attributes\DataProvider;

class SerializerDynamicReturnTypeExtensionTest extends TypeInferenceTestCase
{
    public static function dataFileAsserts(): iterable
    {
        yield from self::gatherAssertTypes(__DIR__ . '/../files/serializer-types.php');
    }

    #[DataProvider('dataFileAsserts')]
    public function testFileAsserts(
        string $assertType,
        string $file,
        mixed ...$args
    ): void {
        $this->assertFileAsserts($assertType, $file, ...$args);
    }

    public static function getAdditionalConfigFiles(): array
    {
        return [__DIR__ . '/../../extension.neon'];
    }
}
