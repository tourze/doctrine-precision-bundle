<?php

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
class PrecisionListener
{
    public function __construct(
        private readonly Inflector $inflector,
    ) {
    }

    public function loadClassMetadata(LoadClassMetadataEventArgs $eventArgs): void
    {
        $cm = $eventArgs->getClassMetadata();

        foreach ($cm->getReflectionClass()->getProperties() as $property) {
            $ormColumn = $property->getAttributes(ORM\Column::class);
            if (empty($ormColumn)) {
                continue;
            }
            $ormColumn = $ormColumn[0]->newInstance();
            /** @var ORM\Column $ormColumn */
            $name = $ormColumn->name;
            if ($name === null || $name === '') {
                $name = $property->getName();
                $name = $this->inflector->toSnakeCase($name);
            }
            if (empty($name)) {
                continue;
            }

            // 特别处理一次带精度的字段
            if (Types::DECIMAL === $ormColumn->type && isset($cm->fieldMappings[$name])) {
                $precisionColumn = $property->getAttributes(PrecisionColumn::class);
                if (!empty($precisionColumn)) {
                    $cm->fieldMappings[$name]['scale'] = intval($_ENV['DEFAULT_PRICE_PRECISION'] ?? 2);
                }
            }
        }
    }
}
