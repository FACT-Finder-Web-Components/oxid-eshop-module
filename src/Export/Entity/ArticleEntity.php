<?php

declare(strict_types=1);

namespace Omikron\FactFinder\Oxid\Export\Entity;

use Omikron\FactFinder\Oxid\Export\Field\FieldInterface;
use OxidEsales\Eshop\Application\Model\Article;

class ArticleEntity implements ExportEntityInterface, DataProviderInterface
{
    /** @var Article */
    protected $article;

    /** @var Article */
    protected $parent;

    /** @var FieldInterface[] */
    protected $fields;

    public function __construct(Article $article, Article $parent, array $fields = [])
    {
        $this->article = $article;
        $this->parent  = $parent;
        $this->fields  = $fields;
    }

    public function toArray(): array
    {
        $data = [
            'ProductNumber' => $this->article->getFieldData('oxartnum'),
            'Master'        => $this->parent->getFieldData('oxartnum'),
            'Name'          => $this->parent->getFieldData('oxtitle'),
            'Short'         => $this->parent->getFieldData('oxshortdesc'),
            'Description'   => $this->parent->getLongDescription(),
            'Price'         => $this->formatNumber((float) $this->article->getBasePrice()),
            'Deeplink'      => $this->parent->getLink(),
            'ImageUrl'      => $this->article->getPictureUrl(),
        ];

        return array_reduce($this->fields, function (array $result, FieldInterface $field): array {
            return [$field->getName() => $field->getValue($this->article, $this->parent)] + $result;
        }, $data);
    }

    public function getEntities(): iterable
    {
        $variants = $this->article->getSimpleVariants();
        if (!$variants) {
            yield $this;
            return;
        }
        foreach ($variants as $variant) {
            yield new static($variant, $this->article, $this->fields);
        }
    }

    protected function formatNumber(float $price): string
    {
        return sprintf('%.02f', $price);
    }
}
