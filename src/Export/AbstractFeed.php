<?php

declare(strict_types=1);

namespace Omikron\FactFinder\Oxid\Export;

use Omikron\FactFinder\Oxid\Export\Field\Attribute as AttributeField;
use Omikron\FactFinder\Oxid\Export\Field\FieldInterface;
use Omikron\FactFinder\Oxid\Export\Stream\StreamInterface;
use Omikron\FactFinder\Oxid\Model\Config\Export as ExportConfig;
use OxidEsales\Eshop\Core\Registry;

abstract class AbstractFeed
{
    /** @var string[] */
    protected $columns;

    abstract public function generate(StreamInterface $stream): void;

    public function getFileName(string $exportType): string
    {
        return sprintf('export%s.%s.csv', $exportType, $this->getChannel(Registry::getLang()->getLanguageAbbr()));
    }

    abstract protected function getAdditionalFields(): array;

    protected function getChannel(string $lang): string
    {
        return Registry::getConfig()->getConfigParam('ffChannel')[$lang];
    }

    protected function getFieldName(FieldInterface $field): string
    {
        return $field->getName();
    }

    protected function getConfigFields(): array
    {
        return array_map(function (string $attribute): FieldInterface {
            return oxNew(AttributeField::class, $attribute);
        }, array_values(oxNew(ExportConfig::class)->getSingleFields()));
    }
}
