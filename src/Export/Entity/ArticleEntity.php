<?php

declare(strict_types=1);

namespace Omikron\FactFinder\Oxid\Export\Entity;

use OxidEsales\Eshop\Application\Model\Article;

class ArticleEntity implements ExportEntityInterface, DataProviderInterface
{
    /** @var Article */
    private $article;

    public function __construct(Article $article)
    {
        $this->article = $article;
    }

    public function toArray(): array
    {
        $manufacturer = $this->article->getManufacturer();
        return [
            'ProductNumber' => $this->article->getFieldData('oxartnum'),
            'Master'        => $this->article->getFieldData('oxartnum'),
            'Name'          => $this->article->getFieldData('oxtitle'),
            'Short'         => $this->article->getFieldData('oxshortdesc'),
            'Description'   => $this->article->getLongDescription(),
            'Brand'         => $manufacturer ? $manufacturer->getTitle() : '',
            'Price'         => $this->formatNumber((float) $this->article->getBasePrice()),
            'Deeplink'      => $this->article->getLink(),
            'ImageUrl'      => $this->article->getPictureUrl(),
        ];
    }

    public function getEntities(): iterable
    {
        yield $this;
    }

    protected function formatNumber(float $price): string
    {
        return sprintf('%.02f', $price);
    }
}
