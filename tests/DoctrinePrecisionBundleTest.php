<?php

namespace Tourze\DoctrinePrecisionBundle\Tests;

use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Tourze\DoctrinePrecisionBundle\DoctrinePrecisionBundle;

class DoctrinePrecisionBundleTest extends TestCase
{
    public function testBundleInstance(): void
    {
        $bundle = new DoctrinePrecisionBundle();

        // 测试是否继承自Bundle基类
        $this->assertInstanceOf(Bundle::class, $bundle);

        // 测试是否为指定的类
        $this->assertInstanceOf(DoctrinePrecisionBundle::class, $bundle);
    }
}
