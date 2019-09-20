<?php

declare(strict_types=1);

namespace Omikron\FactFinder\Oxid\Model;

use Omikron\FactFinder\Oxid\Contract\Export\DataProviderInterface;
use Omikron\FactFinder\Oxid\Contract\Export\Entity\FieldModifierInterface;
use Omikron\FactFinder\Oxid\Contract\Export\StreamInterface;
use Omikron\FactFinder\Oxid\Model\Export\AbstractEntity;
use SplFileObject as File;

class Exporter
{
    public function export(DataProviderInterface $dataProvider, StreamInterface $stream, array $fieldsModifiers = []): File
    {
        $handle = null;
        /** @var AbstractEntity $entity */
        foreach ($dataProvider->getEntities() as $entity) {
            /** @var FieldModifierInterface $fieldsModifier */
            foreach ($fieldsModifiers as $fieldModifier) {
                $entity->setData($fieldModifier->getName(), $fieldModifier->getValue($entity));
            }
            $handle = $stream->addEntity($entity->toArray());
        }

        $handle->rewind();
        return $handle;
    }
}
