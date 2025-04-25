<?php

namespace Tourze\DoctrinePrecisionBundle\Tests\Attribute;

use PHPUnit\Framework\TestCase;
use Tourze\DoctrinePrecisionBundle\Attribute\PrecisionColumn;

class PrecisionColumnTest extends TestCase
{
    public function testPrecisionColumnAttribute(): void
    {
        // 测试属性可以被实例化
        $attribute = new PrecisionColumn();
        $this->assertInstanceOf(PrecisionColumn::class, $attribute);

        // 测试属性被正确设置为类属性目标
        $reflectionClass = new \ReflectionClass(PrecisionColumn::class);
        $attributes = $reflectionClass->getAttributes(\Attribute::class);

        $this->assertCount(1, $attributes);
        $attributeInstance = $attributes[0]->newInstance();

        $this->assertEquals(\Attribute::TARGET_PROPERTY, $attributeInstance->flags);
    }
}
