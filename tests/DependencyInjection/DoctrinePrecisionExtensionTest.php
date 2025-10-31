<?php

declare(strict_types=1);

namespace Tourze\DoctrinePrecisionBundle\Tests\DependencyInjection;

use PHPUnit\Framework\Attributes\CoversClass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Tourze\DoctrinePrecisionBundle\DependencyInjection\DoctrinePrecisionExtension;
use Tourze\DoctrinePrecisionBundle\EventSubscriber\PrecisionListener;
use Tourze\PHPUnitSymfonyUnitTest\AbstractDependencyInjectionExtensionTestCase;
use Yiisoft\Strings\Inflector;

/**
 * @internal
 */
#[CoversClass(DoctrinePrecisionExtension::class)]
final class DoctrinePrecisionExtensionTest extends AbstractDependencyInjectionExtensionTestCase
{
    public function testExtensionLoadsServices(): void
    {
        $container = new ContainerBuilder();
        $container->setParameter('kernel.environment', 'test');
        $extension = new DoctrinePrecisionExtension();

        $extension->load([], $container);

        $this->assertTrue($container->hasDefinition(Inflector::class));
        $this->assertTrue($container->hasDefinition(PrecisionListener::class));
    }
}
