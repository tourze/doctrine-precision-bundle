<?php

declare(strict_types=1);

namespace Tourze\DoctrinePrecisionBundle\Attribute;

/**
 * 标记指定字段是需要使用系统分配的精度值
 */
#[\Attribute(flags: \Attribute::TARGET_PROPERTY)]
class PrecisionColumn
{
}
