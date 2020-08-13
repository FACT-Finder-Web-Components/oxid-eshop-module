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
        return [
            'ProductNumber' => $this->article->getFieldData('oxartnum'),
            'Master'        => $this->article->getFieldData('oxartnum'),
            'Name'          => $this->article->getFieldData('oxtitle'),
            'Description'   => $this->article->getLongDescription(),
            'Deeplink'      => $this->article->getLink(),
            'ImageUrl'      => $this->article->getPictureUrl(),
        ];
    }

    public function getEntities(): iterable
    {
        return [$this];
    }
}
