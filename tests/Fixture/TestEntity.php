<?php

namespace Tourze\DoctrinePrecisionBundle\Tests\Fixture;

use Doctrine\ORM\Mapping as ORM;
use Tourze\DoctrinePrecisionBundle\Attribute\PrecisionColumn;

#[ORM\Entity]
class TestEntity
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: 'decimal', precision: 10)]
    #[PrecisionColumn]
    private ?string $price = null;

    #[ORM\Column(type: 'decimal', precision: 8, scale: 4)]
    private ?string $fixedPrecisionPrice = null;

    public function getId(): ?int
    {
        return $this->id;
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
