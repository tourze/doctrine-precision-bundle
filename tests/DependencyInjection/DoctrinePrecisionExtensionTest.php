<?php

namespace Tourze\DoctrinePrecisionBundle\Tests\DependencyInjection;

use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Tourze\DoctrinePrecisionBundle\DependencyInjection\DoctrinePrecisionExtension;
use Tourze\DoctrinePrecisionBundle\EventSubscriber\PrecisionListener;
use Yiisoft\Strings\Inflector;

class DoctrinePrecisionExtensionTest extends TestCase
{
    public function testExtensionLoadsServices(): void
    {
        // 创建容器
        $container = new ContainerBuilder();

        // 创建扩展
        $extension = new DoctrinePrecisionExtension();

        // 测试加载扩展
        $extension->load([], $container);

        // 验证核心服务是否已注册
        $this->assertTrue($container->hasDefinition(Inflector::class));
        $this->assertTrue($container->hasDefinition(PrecisionListener::class)
            || $container->hasDefinition('Tourze\DoctrinePrecisionBundle\EventSubscriber\PrecisionListener'));
    }
}
