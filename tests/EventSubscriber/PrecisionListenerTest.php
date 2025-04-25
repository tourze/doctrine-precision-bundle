<?php

namespace Tourze\DoctrinePrecisionBundle\Tests\EventSubscriber;

use PHPUnit\Framework\TestCase;
use Tourze\DoctrinePrecisionBundle\Attribute\PrecisionColumn;
use Tourze\DoctrinePrecisionBundle\EventSubscriber\PrecisionListener;
use Yiisoft\Strings\Inflector;

class PrecisionListenerTest extends TestCase
{
    public function testPrecisionListenerWithDefaultPrecision(): void
    {
        // 设置环境变量
        $_ENV['DEFAULT_PRICE_PRECISION'] = '2';

        // 创建实例并测试方法
        $inflector = new Inflector();
        $listener = new PrecisionListener($inflector);

        // 验证监听器已创建
        $this->assertInstanceOf(PrecisionListener::class, $listener);
    }

    public function testPrecisionListenerWithCustomPrecision(): void
    {
        // 设置自定义环境变量
        $_ENV['DEFAULT_PRICE_PRECISION'] = '3';

        // 创建实例并测试
        $inflector = new Inflector();
        $listener = new PrecisionListener($inflector);

        // 验证监听器已创建
        $this->assertInstanceOf(PrecisionListener::class, $listener);
    }

    public function testAttributeAnnotation(): void
    {
        // 测试属性注解配置
        $reflectionClass = new \ReflectionClass(PrecisionColumn::class);
        $attributes = $reflectionClass->getAttributes(\Attribute::class);

        $this->assertCount(1, $attributes);
        $attribute = $attributes[0]->newInstance();

        // 验证属性可以应用于类属性
        $this->assertEquals(\Attribute::TARGET_PROPERTY, $attribute->flags);
    }
}
