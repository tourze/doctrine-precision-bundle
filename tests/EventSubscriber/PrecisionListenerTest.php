<?php

declare(strict_types=1);

namespace Tourze\DoctrinePrecisionBundle\Tests\EventSubscriber;

use BizUserBundle\Entity\BizUser;
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

    public function testLoadClassMetadata(): void
    {
        $em = self::getEntityManager();
        $cm = $em->getClassMetadata(BizUser::class);
        $eventArgs = new LoadClassMetadataEventArgs($cm, $em);
        $listener = self::getService(PrecisionListener::class);

        $listener->loadClassMetadata($eventArgs);

        $this->assertInstanceOf(PrecisionListener::class, $listener);
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
