<?php

declare(strict_types=1);

namespace Omikron\FactFinder\Oxid\Export\Stream;

use Omikron\FactFinder\Oxid\Export\Filter\TextFilter;

class Csv implements StreamInterface
{
    /** @var TextFilter */
    private $filter;

    /** @var resource */
    private $handle;

    /** @var string */
    private $delimiter;

    public function __construct($fileHandle, string $delimiter = ';')
    {
        $this->delimiter = $delimiter;
        $this->handle    = $fileHandle;
        $this->filter    = oxNew(TextFilter::class);
    }

    public function addEntity(array $entity): void
    {
        fputcsv($this->handle, $this->prepare($entity), $this->delimiter);
    }

    private function prepare(array $data): array
    {
        return array_map([$this->filter, 'filterValue'], $data);
    }
}
