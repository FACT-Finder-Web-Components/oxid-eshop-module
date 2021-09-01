<?php


namespace Omikron\FactFinder\Oxid\Export;


use Omikron\FactFinder\Oxid\Export\Entity\DataProvider;
use Omikron\FactFinder\Oxid\Export\Field\Attribute as AttributeField;
use Omikron\FactFinder\Oxid\Export\Field\FieldInterface;
use Omikron\FactFinder\Oxid\Export\Stream\StreamInterface;
use Omikron\FactFinder\Oxid\Model\Config\Export as ExportConfig;
use OxidEsales\Eshop\Core\Registry;

abstract class AbstractFeed
{
    public function generate(StreamInterface $stream): void
    {
        $fields  = array_merge($this->getAdditionalFields(), $this->getConfigFields(), $this->fields);
        $columns = array_unique(array_merge($this->columns, array_map([$this, 'getFieldName'], $fields)));

        $stream->addEntity($columns);
        oxNew(Exporter::class)->exportEntities($stream, oxNew(DataProvider::class, ...$fields), $columns);
    }
    public function getFileName(): string
    {
        return sprintf('export.%s.csv', $this->getChannel(Registry::getLang()->getLanguageAbbr()));
    }

    protected function getFieldName(FieldInterface $field): string
    {
        return $field->getName();
    }

    protected function getChannel(string $lang): string
    {
        return Registry::getConfig()->getConfigParam('ffChannel')[$lang];
    }

    protected function getConfigFields(): array
    {
        return array_map(function (string $attribute): FieldInterface {
            return oxNew(AttributeField::class, $attribute);
        }, array_values(oxNew(ExportConfig::class)->getSingleFields()));
    }
}
