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
        /** @var VariantSelectList[] $variants */
        $variants = $article->getVariantSelections()['selections'] ?? [];

        $attributes = array_reduce($variants, function (string $result, VariantSelectList $variant): string {
            $values = array_map(function (Selection $selection) {
                return $this->filter->filterValue($selection->getName());
            }, $variant->getSelections());

            return $result . $variant->getLabel() . '=' . implode('#', $values) . '|';
        }, '');

        return $attributes ? '|' . $attributes : '';
    }
}
