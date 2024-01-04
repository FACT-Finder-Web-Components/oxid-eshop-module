<?php

declare(strict_types=1);

namespace Omikron\FactFinder\Oxid\Export\Entity;

use Omikron\FactFinder\Oxid\Export\Field\Article\FieldInterface;
use OxidEsales\Eshop\Application\Model\Article;

class ArticleEntity implements ExportEntityInterface, DataProviderInterface
{
    public function __construct(
        protected readonly Article $article,
        protected readonly Article $parent,
        protected readonly array $fields = []
    ) {
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
            'ImageUrl'      => $this->getPictureUrl(),
        ];

        return array_reduce($this->fields, function (array $result, FieldInterface $field): array {
            return [$field->getName() => $field->getValue($this->article, $this->parent)] + $result;
        }, $data);
    }

    public function getEntities(): iterable
    {
        yield $this;

        foreach ($this->article->getSimpleVariants() ?? [] as $variant) {
            yield new static($variant, $this->article, $this->fields);
        }
    }

    protected function formatNumber(float $price): string
    {
        return sprintf('%.02f', $price);
    }

    private function getPictureUrl(): string
    {
        $pictureUrl = $this->article->getPictureUrl();

        if (str_contains($pictureUrl, 'nopic')) {
            $pictureUrl = $this->parent->getPictureUrl();
        }

        return $pictureUrl;
    }
}
