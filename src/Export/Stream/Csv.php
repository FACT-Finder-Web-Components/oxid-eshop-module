<?php

declare(strict_types=1);

namespace Omikron\FactFinder\Oxid\Export\Stream;

class Csv implements StreamInterface
{
    /** @var resource */
    private $handle;

    /** @var string */
    private $delimiter;

    public function __construct($fileHandle, string $delimiter = ';')
    {
        $this->delimiter = $delimiter;
        $this->handle    = $fileHandle;
    }

    public function addEntity(array $entity): void
    {
        fputcsv($this->handle, $entity, $this->delimiter);
    }
}
