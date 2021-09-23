<?php

declare(strict_types=1);

namespace Omikron\FactFinder\Oxid\Export;

use Omikron\FactFinder\Oxid\Export\Entity\DataProviderInterface;
use Omikron\FactFinder\Oxid\Export\Filter\TextFilter;
use Omikron\FactFinder\Oxid\Export\Stream\StreamInterface;

class Exporter implements ExporterInterface
{
    /** @var TextFilter */
    private $filter;

    public function __construct()
    {
        $this->filter = oxNew(TextFilter::class);
    }

    public function exportEntities(StreamInterface $stream, DataProviderInterface $dataProvider, array $columns): void
    {
        $emptyRecord = array_combine($columns, array_fill(0, count($columns), ''));

        foreach ($dataProvider->getEntities() as $entity) {
            $entityData = array_merge($emptyRecord, array_intersect_key($entity->toArray(), $emptyRecord)); // phpcs:ignore
            $stream->addEntity($this->prepare($entityData));
        }
    }

    private function prepare(array $data): array
    {
        return array_map([$this->filter, 'filterValue'], $data);
    }
}
