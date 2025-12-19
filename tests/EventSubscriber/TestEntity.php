<?php

declare(strict_types=1);

namespace Tourze\DoctrinePrecisionBundle\Tests\EventSubscriber;

use Doctrine\ORM\Mapping as ORM;
use Tourze\DoctrinePrecisionBundle\Attribute\PrecisionColumn;

class TestEntity
{
    #[ORM\Column(type: 'decimal')]
    #[PrecisionColumn]
    private null $priceAmount = null;

    #[ORM\Column(type: 'decimal', name: 'custom_price')]
    #[PrecisionColumn]
    private null $customPriceField = null;

    #[ORM\Column(type: 'string')]
    private null $normalField = null;
}
