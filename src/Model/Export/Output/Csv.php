<?php

declare(strict_types=1);

namespace Omikron\FactFinder\Oxid\Model\Export\Output;

use Omikron\FactFinder\Oxid\Contract\Export\StreamInterface;
use Omikron\FactFinder\Oxid\Model\Export\Filter;

class Csv implements StreamInterface
{
    /** @var Filter */
    private $filter;

    /** @var resource */
    private $handle;

    /** @var string */
    private $delimiter;

    public function __construct($fileHandle, string $delimiter = ';')
    {
        $this->delimiter = $delimiter;
        $this->handle    = $fileHandle;
        $this->filter    = oxNew(Filter::class);
    }

    public function addEntity(array $entity)
    {
        fputcsv($this->handle, $this->prepare($entity), $this->delimiter);
    }

    private function prepare(array $data): array
    {
        return array_map([$this->filter, 'filterValue'], $data);
    }
}
