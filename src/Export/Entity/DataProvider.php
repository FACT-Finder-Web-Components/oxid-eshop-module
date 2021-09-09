<?php

declare(strict_types=1);

namespace Omikron\FactFinder\Oxid\Export\Entity;

use Omikron\FactFinder\Oxid\Export\Data\CollectionInterface;
use Omikron\FactFinder\Oxid\Export\Field\FieldInterface;
use OxidEsales\Eshop\Core\Model\MultiLanguageModel;

class DataProvider implements DataProviderInterface
{
    /** @var FieldInterface[] */
    private $fields;

    /** @var CollectionInterface */
    private $collection;

    public function __construct(FieldInterface ...$fields)
    {
        $this->fields = $fields;
    }

    /**
     * @return CollectionInterface
     */
    public function getCollection(): CollectionInterface
    {
        return $this->collection;
    }

    /**
     * @param CollectionInterface $collection
     */
    public function setCollection(CollectionInterface $collection): self
    {
        $this->collection = $collection;

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getEntities(): iterable
    {
        /** @var MultiLanguageModel $collectionElement */
        foreach (oxNew(get_class($this->getCollection())) as $collectionElement) {
            yield from oxNew($this->getCollection()->getEntity(), $collectionElement, $collectionElement, $this->fields)->getEntities();
        }
    }
}
