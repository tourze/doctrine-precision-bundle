<?php

namespace Tourze\DoctrinePrecisionBundle\Tests\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Tourze\DoctrinePrecisionBundle\Attribute\PrecisionColumn;

#[ORM\Entity]
#[ORM\Table(name: 'test_entity', options: ['comment' => 'Test Entity for Precision Bundle'])]
class TestEntity implements \Stringable
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(options: ['comment' => 'ID'])]
    private ?int $id = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, options: ['comment' => '价格'])]
    #[PrecisionColumn]
    private ?string $price = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 8, scale: 4, options: ['comment' => '固定精度价格'])]
    private ?string $fixedPrecisionPrice = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function __toString(): string
    {
        return 'TestEntity #' . ($this->id ?? 'new');
    }

    public function getPrice(): ?string
    {
        return $this->price;
    }

    public function setPrice(?string $price): self
    {
        $this->price = $price;
        return $this;
    }

    public function getFixedPrecisionPrice(): ?string
    {
        return $this->fixedPrecisionPrice;
    }

    public function setFixedPrecisionPrice(?string $fixedPrecisionPrice): self
    {
        $this->fixedPrecisionPrice = $fixedPrecisionPrice;
        return $this;
    }
}
