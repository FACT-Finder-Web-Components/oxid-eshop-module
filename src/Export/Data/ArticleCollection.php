<?php

declare(strict_types=1);

namespace Omikron\FactFinder\Oxid\Export\Data;

use OxidEsales\Eshop\Application\Model\Article;
use OxidEsales\Eshop\Application\Model\ArticleList;

class ArticleCollection implements \IteratorAggregate
{
    /** @var int */
    private $batchSize;

    public function __construct(int $batchSize = 100)
    {
        $this->batchSize = $batchSize;
    }

    /**
     * @return Article[]
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

    protected function getBatch(int $from): ArticleList
    {
        $articleList = oxNew(ArticleList::class);
        $articleList->setBaseObject(new ExportArticle());
        $articleList->setSqlLimit($from * $this->batchSize, $this->batchSize);
        return $articleList->getList();
    }
}
