<?php

declare(strict_types=1);

use JMS\Serializer\ArrayTransformerInterface;
use JMS\Serializer\SerializerInterface;
use function PHPStan\Testing\assertType;

class Bar {}

/**
 * @param class-string<Bar> $className
 */
function deserialize(SerializerInterface $serializer, string $className): void
{
    assertType(Bar::class, $serializer->deserialize('{}', Bar::class, 'json'));
    assertType(Bar::class, $serializer->deserialize('{}', $className, 'json'));
}

/**
 * @param class-string<Bar> $className
 */
function fromArray(ArrayTransformerInterface $transformer, string $className): void
{
    assertType(Bar::class, $transformer->fromArray([], Bar::class));
    assertType(Bar::class, $transformer->fromArray([], $className));
}
