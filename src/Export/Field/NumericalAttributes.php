<?php

declare(strict_types=1);

namespace Omikron\FactFinder\Oxid\Export\Field;

use Omikron\FactFinder\Oxid\Export\Data\ExportAttribute;
use Omikron\FactFinder\Oxid\Export\Filter\ExtendedTextFilter;
use Omikron\FactFinder\Oxid\Model\Config\Export as ExportConfig;
use OxidEsales\Eshop\Application\Model\Article;

class NumericalAttributes extends Attribute
{
    /** @var ExtendedTextFilter */
    private $filter;

    /** @var ExportConfig */
    private $exportConfig;

    public function __construct()
    {
        $this->filter       = oxNew(ExtendedTextFilter::class);
        $this->exportConfig = oxNew(ExportConfig::class);
    }

    public function getName(): string
    {
        return 'NumericalAttributes';
    }

    public function getValue(Article $article, Article $parent): string
    {
        $selectedAttributes = $this->exportConfig->getNumericalFields();
        $data               = parent::getData($article);

        $numericalAttributes =  array_reduce($selectedAttributes, function (string $result, ExportAttribute $attribute) use ($data) {
            $title = $attribute->getFieldData('oxtitle');
            return $result . (isset($data[$title])
                    ? $this->filter->filterValue($title) . '=' . (string)($data[$title]) . '|'
                    : '');
        }, '');

        return $numericalAttributes ? '|' . $numericalAttributes : '';
    }
}
