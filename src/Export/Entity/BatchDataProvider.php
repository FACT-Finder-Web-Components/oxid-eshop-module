<?php

declare(strict_types=1);

namespace Omikron\FactFinder\Oxid\Export\Entity;

use Omikron\FactFinder\Oxid\Export\Data\ArticleCollection;
use Omikron\FactFinder\Oxid\Export\Field\Article\FieldInterface;

class BatchDataProvider implements DataProviderInterface
{
    /** @var FieldInterface[] */
    private array $fields;

    public function __construct(
        private readonly ArticleCollection $collection,
        private readonly int $offset,
        private readonly int $limit,
        FieldInterface ...$fields,
    ) {
        $this->fields = $fields;
    }

    public function getEntities(): iterable
    {
        $batch = $this->collection->getBatchByOffset(
            $this->offset,
            $this->limit
        );

        foreach ($batch as $item) {
            yield from oxNew(
                $this->collection->getEntity(),
                $item,
                $item,
                $this->fields
            )->getEntities();
        }
    }
}
