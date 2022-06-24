<?php

declare(strict_types=1);

namespace Tests\Variant\Export\Data;

use Omikron\FactFinder\Oxid\Export\Data\ArticleCollection;
use OxidEsales\Eshop\Application\Model\Article;
use OxidEsales\Eshop\Core\Model\ListModel;

class ArticleCollectionVariant extends ArticleCollection
{
    /** @var ListModel */
    private $list;

    /**
     * @param \OxidEsales\Eshop\Core\Model\MultiLanguageModel[] $items
     */
    public function __construct(array $items)
    {
        $this->list = new ListModel();

        foreach ($items as $item) {
            $this->list->add($item);
        }

        parent::__construct();
    }

    /**
     * @return Article[]
     */
    public function getIterator(): iterable
    {
        return $this->list;
    }

    public function getBatch(int $from): ListModel
    {
        return $this->list;
    }
}
