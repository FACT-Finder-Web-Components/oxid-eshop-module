<?php

declare(strict_types=1);

namespace Omikron\FactFinder\Oxid\Export;

use Omikron\FactFinder\Oxid\Export\Data\ExportAttribute;
use Omikron\FactFinder\Oxid\Export\Entity\DataProvider;
use Omikron\FactFinder\Oxid\Export\Field\Attribute as AttributeField;
use Omikron\FactFinder\Oxid\Export\Field\Brand;
use Omikron\FactFinder\Oxid\Export\Field\CategoryPath;
use Omikron\FactFinder\Oxid\Export\Field\FieldInterface;
use Omikron\FactFinder\Oxid\Export\Field\FilterAttributes;
use Omikron\FactFinder\Oxid\Export\Field\Keywords;
use Omikron\FactFinder\Oxid\Export\Field\NumericalAttributes;
use Omikron\FactFinder\Oxid\Export\Stream\StreamInterface;
use Omikron\FactFinder\Oxid\Model\Config\Export as ExportConfig;
use OxidEsales\Eshop\Core\Registry;

class ArticleFeed
{
    /** @var FieldInterface[] */
    protected $fields;

    /** @var string[] */
    protected $columns = [
        'ProductNumber',
        'Master',
        'Name',
        'Short',
        'Description',
        'Price',
        'Deeplink',
        'ImageUrl',
    ];

    /** @var ExportConfig */
    protected $exportConfig;

    public function __construct(FieldInterface ...$fields)
    {
        $this->fields       = $fields;
        $this->exportConfig =  oxNew(ExportConfig::class);
    }

    public function getFileName(): string
    {
        return sprintf('export.%s.csv', $this->getChannel(Registry::getLang()->getLanguageAbbr()));
    }

    public function generate(StreamInterface $stream): void
    {
        $fields  = array_merge($this->getAdditionalFields(), $this->fields);
        $columns = array_unique(array_merge($this->columns, array_map([$this, 'getFieldName'], $fields)));

        $stream->addEntity($columns);
        oxNew(Exporter::class)->exportEntities($stream, oxNew(DataProvider::class, ...$fields), $columns);
    }

    protected function getChannel(string $lang): string
    {
        return Registry::getConfig()->getConfigParam('ffChannel')[$lang];
    }

    private function getFieldName(FieldInterface $field): string
    {
        return $field->getName();
    }

    protected function getAdditionalFields(): array
    {
        return array_merge([
           oxNew(Brand::class),
           oxNew(CategoryPath::class),
           oxNew(FilterAttributes::class),
           oxNew(NumericalAttributes::class),
           oxNew(Keywords::class),
        ], array_map(function (ExportAttribute $attribute): AttributeField {
            return new AttributeField($attribute->getFieldData('oxtitle'));
        }, $this->exportConfig->getSingleFields()));
    }
}
