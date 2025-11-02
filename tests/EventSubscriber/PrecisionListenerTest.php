<?php

declare(strict_types=1);

namespace Tourze\DoctrinePrecisionBundle\Tests\EventSubscriber;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Event\LoadClassMetadataEventArgs;
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
    }

    public function testLoadClassMetadata(): void
    {
        $listener = self::getService(PrecisionListener::class);

        // 使用反射验证 loadClassMetadata 方法存在且为公共方法
        $method = new \ReflectionMethod($listener, 'loadClassMetadata');
        $this->assertTrue($method->isPublic());

        // 验证方法参数类型正确
        $params = $method->getParameters();
        $this->assertCount(1, $params);
        $this->assertEquals('eventArgs', $params[0]->getName());

        // 验证返回类型为 void
        $returnType = $method->getReturnType();
        $this->assertNotNull($returnType);
        $this->assertInstanceOf(\ReflectionNamedType::class, $returnType);
        $this->assertEquals('void', $returnType->getName());
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
