<?php

declare(strict_types=1);

namespace Omikron\FactFinder\Oxid\Export\Entity;

use Omikron\FactFinder\Oxid\Export\Data\ArticleCollection;
use Omikron\FactFinder\Oxid\Export\Field\FieldInterface;

class DataProvider implements DataProviderInterface
{
    /** @var FieldInterface[] */
    private $fields;

    public function __construct(FieldInterface ...$fields)
    {
        $this->fields = $fields;
    }

    public function getEntities(): iterable
    {
        foreach (oxNew(ArticleCollection::class) as $article) {
            yield from oxNew(ArticleEntity::class, $article, $article, $this->fields)->getEntities();
        }
    }
}
