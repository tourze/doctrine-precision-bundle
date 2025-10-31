<?php

declare(strict_types=1);

namespace Tourze\DoctrinePrecisionBundle\Tests;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use Tourze\DoctrinePrecisionBundle\DoctrinePrecisionBundle;
use Tourze\PHPUnitSymfonyKernelTest\AbstractBundleTestCase;

/**
 * @internal
 */
#[CoversClass(DoctrinePrecisionBundle::class)]
#[RunTestsInSeparateProcesses]
#[Group('skip-database-setup')]
final class DoctrinePrecisionBundleTest extends AbstractBundleTestCase
{
}
