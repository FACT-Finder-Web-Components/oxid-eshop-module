<?php

declare(strict_types=1);

namespace Omikron\FactFinder\Oxid\Export\Field;

use OxidEsales\Eshop\Application\Model\Article;
use OxidEsales\Eshop\Application\Model\Attribute as ArticleAttribute;

class Attribute implements FieldInterface
{
    /** @var string */
    private $name;

    public function __construct(string $name)
    {
        $this->name = $name;
    }


    public function getName(): string
    {
        return $this->name;
    }

    public function getValue(Article $article, Article $parent): string
    {
        return (string) ($this->getData($article)[$this->name] ?? '');
    }

    protected function getData(Article $article): array
    {
        $attributes = $article->getAttributes()->getArray();
        return array_reduce($attributes, function (array $result, ArticleAttribute $attribute) {
            return $result + [$attribute->oxattribute__oxtitle->rawValue => $attribute->getFieldData('oxvalue')];
        }, []);
    }
}
