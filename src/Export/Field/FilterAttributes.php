<?php

declare(strict_types=1);

namespace Omikron\FactFinder\Oxid\Export\Field;

use Omikron\FactFinder\Oxid\Export\Field\Article\FieldInterface;
use Omikron\FactFinder\Oxid\Export\Filter\ExtendedTextFilter;
use Omikron\FactFinder\Oxid\Model\Config\Export as ExportConfig;
use OxidEsales\Eshop\Application\Model\Article;
use OxidEsales\Eshop\Application\Model\Selection;
use OxidEsales\Eshop\Application\Model\VariantSelectList;

class FilterAttributes extends Attribute implements FieldInterface
{
    /** @var ExtendedTextFilter */
    private $filter;

    /** @var string[] */
    private $multiAttributes;

    public function __construct()
    {
        parent::__construct('FilterAttributes');
        $this->filter = oxNew(ExtendedTextFilter::class);
    }

    public function getValue(Article $article, Article $parent): string
    {
        $attributes = ($article === $parent ? $this->getAllValues($article) : $this->getVariantValues($article, $parent))
            . $this->getSelectedFilterAttributes($article);

        return $attributes ? '|' . $attributes : '';
    }

    protected function getVariantValues(Article $article, Article $parent): string
    {
        $oxvarname   = $parent->getFieldData('oxvarname');
        $oxvarselect = $article->getFieldData('oxvarselect');

        return implode('', array_map(function (string $key, ?string $value): string {
            return $this->filter->filterValue($key) . '=' . $this->filter->filterValue($value) . '|';
        }, ...array_map(function (?string $value): array {
            return explode(' | ', $value);
        }, [$this->validateValueForExport($oxvarname), $this->validateValueForExport($oxvarselect)])));
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
        $result = '';
        foreach (array_intersect_key(parent::getData($article), $this->getMultiAttributes()) as $key => $value) {
            if ($value) {
                $result .= $this->filter->filterValue($key) . '=' . $this->filter->filterValue((string) $value) . '|';
            }
        }
        return $result;
    }

    private function getMultiAttributes(): array
    {
        $this->multiAttributes = $this->multiAttributes ?? array_flip(oxNew(ExportConfig::class)->getMultiAttributes());
        return $this->multiAttributes;
    }

    private function validateValueForExport($exportValue): string
    {
        return is_numeric($exportValue) || is_string($exportValue) ? (string) $exportValue : '';
    }
}
