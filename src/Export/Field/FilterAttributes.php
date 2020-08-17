<?php

declare(strict_types=1);

namespace Omikron\FactFinder\Oxid\Export\Field;

use Omikron\FactFinder\Oxid\Export\Filter\ExtendedTextFilter;
use OxidEsales\Eshop\Application\Model\Article;
use OxidEsales\Eshop\Application\Model\Selection;
use OxidEsales\Eshop\Application\Model\VariantSelectList;

class FilterAttributes implements FieldInterface
{
    /** @var ExtendedTextFilter */
    private $filter;

    public function __construct()
    {
        $this->filter = oxNew(ExtendedTextFilter::class);
    }

    public function getName(): string
    {
        return 'FilterAttributes';
    }

    public function getValue(Article $article, Article $parent): string
    {
        $attributes = $article === $parent ? $this->getAllValues($article) : $this->getVariantValues($article, $parent);
        return $attributes ? '|' . $attributes : '';
    }

    protected function getVariantValues(Article $article, Article $parent): string
    {
        return implode('', array_map(function (string $key, string $value): string {
            return $this->filter->filterValue($key) . '=' . $this->filter->filterValue($value) . '|';
        }, ...array_map(function (string $value): array {
            return explode(' | ', $value);
        }, [$parent->getFieldData('oxvarname'), $article->getFieldData('oxvarselect')])));
    }

    protected function getAllValues(Article $article): string
    {
        $variants = $article->getVariantSelections()['selections'] ?? [];
        return array_reduce($variants, function (string $result, VariantSelectList $variant): string {
            $values = array_map(function (Selection $selection) {
                return $this->filter->filterValue($selection->getName());
            }, $variant->getSelections());
            return $result . $this->filter->filterValue($variant->getLabel()) . '=' . implode('#', $values) . '|';
        }, '');
    }
}
