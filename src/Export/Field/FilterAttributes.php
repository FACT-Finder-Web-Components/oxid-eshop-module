<?php

declare(strict_types=1);

namespace Omikron\FactFinder\Oxid\Export\Field;

use Omikron\FactFinder\Oxid\Export\Filter\ExtendedTextFilter;
use Omikron\FactFinder\Oxid\Model\Config\Export as ExportConfig;
use OxidEsales\Eshop\Application\Model\Article;
use OxidEsales\Eshop\Application\Model\Selection;
use OxidEsales\Eshop\Application\Model\VariantSelectList;

class FilterAttributes extends Attribute implements FieldInterface
{
    /** @var ExtendedTextFilter */
    private $filter;

    /** @var ExportConfig */
    private $exportConfig;

    public function __construct()
    {
        parent::__construct('FilterAttributes');
        $this->filter       = oxNew(ExtendedTextFilter::class);
        $this->exportConfig = oxNew(ExportConfig::class);
    }

    public function getValue(Article $article, Article $parent): string
    {
        $attributes = $article === $parent
            ? $this->getAllValues($article) . $this->getSelectedFilterAttributes($article)
            : $this->getVariantValues($article, $parent);

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
            $values = array_map(function (Selection $selection): string {
                return $this->filter->filterValue($selection->getName());
            }, $variant->getSelections());
            return $result . $this->filter->filterValue($variant->getLabel()) . '=' . implode('#', $values) . '|';
        }, '');
    }

    private function getSelectedFilterAttributes(Article $article): string
    {
        $selectedAttributes = $this->exportConfig->getMultiAttributes();
        $data               = parent::getData($article);

        return array_reduce($selectedAttributes, function (string $result, string $attribute) use ($data) {
            $value = $data[$attribute] ?? '';
            return $result . ($value
                    ? $this->filter->filterValue($attribute) . '=' . $this->filter->filterValue((string) $value) . '|'
                    : '');
        }, '');
    }
}
