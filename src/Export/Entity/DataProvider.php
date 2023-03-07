<?php

declare(strict_types=1);

namespace Omikron\FactFinder\Oxid\Export\Entity;

use Omikron\FactFinder\Oxid\Export\Data\CollectionInterface;
use Omikron\FactFinder\Oxid\Export\Field\Article\FieldInterface as ArticleField;
use Omikron\FactFinder\Oxid\Export\Field\Category\FieldInterface as CategoryField;
use OxidEsales\Eshop\Core\Model\MultiLanguageModel;

class DataProvider implements DataProviderInterface
{
    /** @var ArticleField[]|CategoryField[] */
    private $fields;

    /** @var CollectionInterface */
    private $collection;

    public function __construct(CollectionInterface $collection, ...$fields)
    {
        $this->collection = $collection;
        $this->fields     = $fields;
    }

    /**
     * {@inheritDoc}
     */
    public function getEntities(): iterable
    {
        /** @var MultiLanguageModel $item */
        foreach ($this->collection as $item) {
            yield from oxNew($this->collection->getEntity(), $item, $item, $this->fields)->getEntities();
        }
    }
}
