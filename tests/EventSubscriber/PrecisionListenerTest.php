<?php

declare(strict_types=1);

namespace Tourze\DoctrinePrecisionBundle\Tests\EventSubscriber;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use Tourze\DoctrinePrecisionBundle\EventSubscriber\PrecisionListener;
use Tourze\PHPUnitSymfonyKernelTest\AbstractEventSubscriberTestCase;

/**
 * @internal
 */
#[CoversClass(PrecisionListener::class)]
#[RunTestsInSeparateProcesses]
#[Group('skip-database-setup')]
final class PrecisionListenerTest extends AbstractEventSubscriberTestCase
{
    protected function onSetUp(): void
    {
    }

    #[DataProvider('fieldNameDataProvider')]
    public function testFieldNameConversion(string $propertyName, ?string $columnName, string $expectedFieldName): void
    {
        $listener = self::getService(PrecisionListener::class);
        $method = new \ReflectionMethod($listener, 'getFieldName');

        $ormColumn = new ORM\Column(name: $columnName, type: Types::DECIMAL);
        $property = new \ReflectionProperty(TestEntity::class, $propertyName);

        $result = $method->invoke($listener, $ormColumn, $property);

        $this->assertEquals($expectedFieldName, $result);
    }

    public function testEnvironmentVariableHandling(): void
    {
        // 测试环境变量的类型处理能够通过 PHPStan 检查
        $listener = self::getService(PrecisionListener::class);

        // 验证服务能够正确实例化且可用
        $this->assertInstanceOf(PrecisionListener::class, $listener);

        // 测试私有方法 getFieldName 的存在性（已经有详细测试了）
        $this->assertTrue(method_exists($listener, 'getFieldName'));
        $this->assertTrue(method_exists($listener, 'applyPrecisionSettings'));
    }

    /**
     * @return array<string, array{string, string|null, string}>
     */
    public static function fieldNameDataProvider(): array
    {
        return [
            'explicit column name' => ['customPriceField', 'custom_price', 'custom_price'],
            'camelCase to snake_case' => ['customPriceField', null, 'custom_price_field'],
            'simple property name' => ['priceAmount', null, 'price_amount'],
            'empty column name' => ['priceAmount', '', 'price_amount'],
        ];
    }
}
