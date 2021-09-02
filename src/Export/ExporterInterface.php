<?php

namespace Omikron\FactFinder\Oxid\Export;

use Omikron\FactFinder\Oxid\Export\Data\CollectionInterface;
use Omikron\FactFinder\Oxid\Export\Entity\DataProviderInterface;
use Omikron\FactFinder\Oxid\Export\Stream\StreamInterface;

interface ExporterInterface
{
    /**
     * @param StreamInterface $stream
     * @param DataProviderInterface $dataProvider
     * @param array $columns
     * @param CollectionInterface $collection
     */
    public function exportEntities(StreamInterface $stream, DataProviderInterface $dataProvider, array $columns, CollectionInterface $collection): void;
}
