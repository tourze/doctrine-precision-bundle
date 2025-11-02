<?php

declare(strict_types=1);

namespace Tourze\DoctrinePrecisionBundle\EventSubscriber;

use Doctrine\Bundle\DoctrineBundle\Attribute\AsDoctrineListener;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Event\LoadClassMetadataEventArgs;
use Doctrine\ORM\Events;
use Doctrine\ORM\Mapping as ORM;
use Tourze\DoctrinePrecisionBundle\Attribute\PrecisionColumn;
use Yiisoft\Strings\Inflector;

/**
 * 自动补充索引 & 精度值
 *
 * @see https://alexkunin.medium.com/doctrine-symfony-adding-indexes-to-fields-defined-in-traits-a8e480af66b2
 */
#[AsDoctrineListener(event: Events::loadClassMetadata)]
readonly class PrecisionListener
{
    public function __construct(
        private Inflector $inflector,
    ) {
    }

    public function loadClassMetadata(LoadClassMetadataEventArgs $eventArgs): void
    {
        $cm = $eventArgs->getClassMetadata();

        foreach ($cm->getReflectionClass()->getProperties() as $property) {
            $this->processPrecisionProperty($cm, $property);
        }
    }

    /**
     * @param ORM\ClassMetadata<object> $cm
     */
    private function processPrecisionProperty(ORM\ClassMetadata $cm, \ReflectionProperty $property): void
    {
        $ormColumn = $property->getAttributes(ORM\Column::class);
        if ([] === $ormColumn) {
            return;
        }

        $ormColumn = $ormColumn[0]->newInstance();
        /** @var ORM\Column $ormColumn */
        $name = $this->getFieldName($ormColumn, $property);
        if ('' === $name) {
            return;
        }

        $this->applyPrecisionSettings($cm, $property, $ormColumn, $name);
    }

    private function getFieldName(ORM\Column $ormColumn, \ReflectionProperty $property): string
    {
        $name = $ormColumn->name;
        if (null === $name || '' === $name) {
            $name = $property->getName();
            $name = $this->inflector->toSnakeCase($name);
        }

        return $name;
    }

    /**
     * @param ORM\ClassMetadata<object> $cm
     */
    private function applyPrecisionSettings(ORM\ClassMetadata $cm, \ReflectionProperty $property, ORM\Column $ormColumn, string $name): void
    {
        if (Types::DECIMAL !== $ormColumn->type || !isset($cm->fieldMappings[$name])) {
            return;
        }

        $precisionColumn = $property->getAttributes(PrecisionColumn::class);
        if ([] !== $precisionColumn) {
            $envPrecision = $_ENV['DEFAULT_PRICE_PRECISION'] ?? '2';
            assert(is_string($envPrecision) || is_int($envPrecision) || is_float($envPrecision));
            $cm->fieldMappings[$name]->scale = intval($envPrecision);
        }
    }
}
