<?php

declare(strict_types=1);

namespace Omikron\FactFinder\Oxid\Model\Export\Output;

use Omikron\FactFinder\Oxid\Contract\Export\StreamInterface;
use Omikron\FactFinder\Oxid\Model\Export\Filter;
use OxidEsales\Eshop\Core\Config;
use OxidEsales\Eshop\Core\Registry;
use SplFileObject as File;

class Csv implements StreamInterface
{
    /** @var Config */
    private $config;

    /** @var Filter */
    private $filter;

    /** @var resource */
    private $handle;

    /** @var string */
    private $delimiter;

    public function __construct(string $delimiter = ';')
    {
        $this->delimiter = $delimiter;
        $this->config    = Registry::getConfig();
        $this->filter    = oxNew(Filter::class);
        $this->handle    = new File('php://temp', 'wr+');
    }

    public function addEntity(array $entity): File
    {
        $this->handle->fputcsv($this->prepare($entity), $this->delimiter);
        return $this->handle;
    }

    private function prepare(array $data): array
    {
        return array_map([$this->filter, 'filterValue'], $data);
    }
}
