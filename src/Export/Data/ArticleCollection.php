<?php

declare(strict_types=1);

namespace Omikron\FactFinder\Oxid\Export\Data;

use Omikron\FactFinder\Oxid\Export\Entity\ArticleEntity;
use OxidEsales\Eshop\Application\Model\Article;
use OxidEsales\Eshop\Application\Model\ArticleList;
use OxidEsales\Eshop\Core\Model\ListModel;

class ArticleCollection implements \IteratorAggregate, CollectionInterface
{
    public function __construct(private readonly int $batchSize = 100)
    {
    }

    public function getEntity(): string
    {
        return ArticleEntity::class;
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

    public function getBatch(int $from): ListModel
    {
        $articleList = oxNew(ArticleList::class);
        $articleList->setBaseObject(oxNew(Article::class));
        $articleList->setSqlLimit($from * $this->batchSize, $this->batchSize);

        $article  = $articleList->getBaseObject();
        $viewName = $article->getViewName();
        $active   = $article->getSqlActiveSnippet();
        $query    = "SELECT {$article->getSelectFields()} FROM `{$viewName}` WHERE (`{$viewName}`.`oxparentid` = '')";
        $articleList->selectString($query . ($active ? ' AND ' . $active : ''));

        return $articleList;
    }
}
