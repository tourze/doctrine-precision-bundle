<?php

declare(strict_types=1);

namespace Tourze\DoctrinePrecisionBundle\Tests\Attribute;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Tourze\DoctrinePrecisionBundle\Attribute\PrecisionColumn;

/**
 * @internal
 */
#[CoversClass(PrecisionColumn::class)]
final class PrecisionColumnTest extends TestCase
{
    public function testPrecisionColumnAttribute(): void
    {
        $reflectionClass = new \ReflectionClass(PrecisionColumn::class);
        $attributes = $reflectionClass->getAttributes(\Attribute::class);

        $this->assertCount(1, $attributes);
        $attributeInstance = $attributes[0]->newInstance();

        $this->assertEquals(\Attribute::TARGET_PROPERTY, $attributeInstance->flags);
    }
}
