<?php

declare(strict_types=1);

namespace Omikron\FactFinder\Oxid\Export\Data;

use Omikron\FactFinder\Oxid\Export\Entity\CategoryEntity;
use OxidEsales\Eshop\Application\Model\Category;
use OxidEsales\Eshop\Application\Model\CategoryList;
use OxidEsales\Eshop\Core\Model\ListModel;

class CategoryCollection implements \IteratorAggregate, CollectionInterface
{
    /** @var int */
    private $batchSize;

    public function __construct(int $batchSize = 100)
    {
        $this->batchSize = $batchSize;
    }

    public function getEntity(): string
    {
        return CategoryEntity::class;
    }

    /**
     * @return Category[]
     */
    public function getIterator(): iterable
    {
        yield from []; // Init iterator

        $from  = 0;
        $batch = $this->getBatch($from);
        while ($batch->count()) {
            yield from $batch;
            $batch = $this->getBatch(++$from);
        }
    }

    public function getBatch(int $from): ListModel
    {
        $categoryList = oxNew(CategoryList::class);
        $categoryList->setBaseObject(oxNew(Category::class));
        $categoryList->setSqlLimit($from * $this->batchSize, $this->batchSize);

        $category = $categoryList->getBaseObject();
        $viewName = $category->getViewName();
        $active   = $category->getSqlActiveSnippet();
        $query    = "SELECT {$category->getSelectFields()} FROM `{$viewName}` WHERE 1";
        $categoryList->selectString($query . ($active ? ' AND ' . $active : ''));

        return $categoryList;
    }
}
