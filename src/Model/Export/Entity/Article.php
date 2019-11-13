<?php

declare(strict_types=1);

namespace Omikron\FactFinder\Oxid\Model\Export\Entity;

use Iterator;
use Omikron\FactFinder\Oxid\Contract\Export\DataProviderInterface;
use Omikron\FactFinder\Oxid\Contract\Export\ExportEntityInterface;
use Omikron\FactFinder\Oxid\Model\Db\Article\Variant\RecordsFactory as VariantRecordFactory;
use Omikron\FactFinder\Oxid\Model\Db\Records;
use Omikron\FactFinder\Oxid\Model\Export\AbstractEntity;
use Omikron\FactFinder\Oxid\Model\Export\Entity\Article\Variant;

class Article extends AbstractEntity implements DataProviderInterface, ExportEntityInterface
{
    /** @var Records */
    private $variantRecords;

    /** @var array */
    private $attributes = [];

    public function __construct(array $data = [])
    {
        parent::__construct($data);
        $this->variantRecords = oxNew(VariantRecordFactory::class)->create($this->getOxidId());
    }

    public function toArray(): array
    {
        $attributes = array_unique(array_filter(array_reduce($this->attributes, function (array $result, string $item) {
            array_push($result, '', ...explode('|', $item));
            return $result;
        }, [])));
        $this->setData('Attributes', $attributes ? ('|' . implode('|', $attributes) . '|') : '');
        return $this->data;
    }

    public function getEntities(): Iterator
    {
        $this->attributes = [$this->getOxidId() => $this->getAttributes()];
        foreach ($this->variantRecords->getRecords() as $articleRow) {
            $variant = new Variant($articleRow);
            yield $variant;
            $this->attributes[$variant->getOxidId()] = $variant->getAttributes();
        }
        yield $this;
    }
}
