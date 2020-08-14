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
    protected $variant;

    /** @var FieldInterface[] */
    protected $fields;

    public function __construct(Article $article, Article $variant, array $fields = [])
    {
        $this->article = $article;
        $this->variant = $variant;
        $this->fields  = $fields;
    }

    public function toArray(): array
    {
        $data = [
            'ProductNumber' => $this->variant->getFieldData('oxartnum'),
            'Master'        => $this->article->getFieldData('oxartnum'),
            'Name'          => $this->article->getFieldData('oxtitle'),
            'Short'         => $this->article->getFieldData('oxshortdesc'),
            'Description'   => $this->article->getLongDescription(),
            'Price'         => $this->formatNumber((float) $this->variant->getBasePrice()),
            'Deeplink'      => $this->article->getLink(),
            'ImageUrl'      => $this->variant->getPictureUrl(),
        ];

        return array_reduce($this->fields, function (array $result, FieldInterface $field): array {
            return [$field->getName() => $field->getValue($this->article)] + $result;
        }, $data);
    }

    public function getEntities(): iterable
    {
        yield $this;
        foreach ($this->article->getSimpleVariants() ?? [] as $variant) {
            yield new static($this->article, $variant, $this->fields);
        }
    }

    protected function formatNumber(float $price): string
    {
        return sprintf('%.02f', $price);
    }
}
