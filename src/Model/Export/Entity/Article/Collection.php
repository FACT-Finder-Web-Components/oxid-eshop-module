<?php

declare(strict_types=1);

namespace Omikron\FactFinder\Oxid\Model\Export\Entity\Article;

use Iterator;
use Omikron\FactFinder\Oxid\Contract\Export\ColumnProviderInterface;
use Omikron\FactFinder\Oxid\Contract\Export\DataProviderInterface;
use Omikron\FactFinder\Oxid\Model\Db\Article\RecordsFactory;
use Omikron\FactFinder\Oxid\Model\Db\Records;
use Omikron\FactFinder\Oxid\Model\Export\Entity\Article;

class Collection implements DataProviderInterface, ColumnProviderInterface
{
    /** @var Records */
    private $records;

    public function __construct()
    {
        $this->records = oxNew(RecordsFactory::class)->create();
    }

    public function getEntities(): Iterator
    {
        yield from [];
        foreach ($this->records->getRecords() as $articleRow) {
            $parent = new Article($articleRow);
            yield from $parent->getEntities();
        }
    }

    public function getFields(): array
    {
        return $this->records->getColumns();
    }
}
