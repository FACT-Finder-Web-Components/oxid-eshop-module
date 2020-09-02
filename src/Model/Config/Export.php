<?php

declare(strict_types=1);

namespace Omikron\FactFinder\Oxid\Model\Config;

use Omikron\FactFinder\Oxid\Export\Data\ExportAttribute;
use OxidEsales\EshopCommunity\Application\Model\AttributeList;
use OxidEsales\Eshop\Core\Config;
use OxidEsales\Eshop\Core\Registry;

class Export
{
    private const ATTRIBUTE_TO_EXPORT_PATH = 'ffExportAttributes';

    /** @var Config */
    private $config;

    public function __construct()
    {
        $this->config = Registry::getConfig();
    }

    public function getMultiAttributes(): array
    {
        $config = $this->getSelectedAttributesConfig();
        return array_values(array_filter($this->getAttributes(), function (ExportAttribute $attribute) use ($config): bool {
            return !!$config[$attribute->getFieldData('oxid')];
        }));
    }

    public function getSingleFields(): array
    {
        $config = $this->getSelectedAttributesConfig();
        return array_values(array_filter($this->getAttributes(), function (ExportAttribute $attribute) use ($config): bool {
            return !$config[$attribute->getFieldData('oxid')];
        }));
    }

    public function getSelectedAttributesConfig(): array
    {
        return $this->config->getConfigParam(self::ATTRIBUTE_TO_EXPORT_PATH);
    }

    private function getAttributes(): array
    {
        $attributeList = oxNew(AttributeList::class);
        $attributeList->setBaseObject(oxNew(ExportAttribute::class));

        return $attributeList->getList()->getArray();
    }
}
