<?php

declare(strict_types=1);

namespace Omikron\FactFinder\Oxid\Export;

use Omikron\FactFinder\Oxid\Export\Entity\DataProviderInterface;
use Omikron\FactFinder\Oxid\Export\Stream\StreamInterface;

interface ExporterInterface
{
    /**
     * @param StreamInterface       $stream
     * @param DataProviderInterface $dataProvider
     * @param array                 $columns
     */
    public function exportEntities(StreamInterface $stream, DataProviderInterface $dataProvider, array $columns): void;
}
