<?php

declare(strict_types=1);

namespace Omikron\FactFinder\Oxid\Model;

use Omikron\FactFinder\Oxid\Contract\Export\DataProviderInterface;
use Omikron\FactFinder\Oxid\Contract\Export\Entity\FieldModifierInterface;
use Omikron\FactFinder\Oxid\Contract\Export\StreamInterface;
use Omikron\FactFinder\Oxid\Model\Export\AbstractEntity;

class Exporter
{
    /**
     * @param DataProviderInterface    $dataProvider
     * @param StreamInterface          $stream
     * @param FieldModifierInterface[] $fieldsModifiers
     */
    public function export(DataProviderInterface $dataProvider, StreamInterface $stream, array $fieldsModifiers = [])
    {
        /** @var AbstractEntity $entity */
        foreach ($dataProvider->getEntities() as $entity) {
            foreach ($fieldsModifiers as $fieldModifier) {
                $entity->setData($fieldModifier->getName(), $fieldModifier->getValue($entity));
            }
            $stream->addEntity($entity->toArray());
        }
    }
}
