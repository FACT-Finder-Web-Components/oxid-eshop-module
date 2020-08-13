<?php

declare(strict_types=1);

namespace Omikron\FactFinder\Oxid\Export\Entity;

use Omikron\FactFinder\Oxid\Export\Data\ArticleCollection;

class DataProvider implements DataProviderInterface
{
    /**
     * @inheritDoc
     */
    public function getEntities(): iterable
    {
        foreach (oxNew(ArticleCollection::class) as $article) {
            yield from (new ArticleEntity($article))->getEntities();
        }
    }
}
