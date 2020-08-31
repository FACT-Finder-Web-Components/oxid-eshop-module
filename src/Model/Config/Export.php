<?php

declare(strict_types=1);

namespace Omikron\FactFinder\Oxid\Model\Config;

use Omikron\FactFinder\Oxid\Export\Data\ExportAttribute;
use OxidEsales\EshopCommunity\Application\Model\AttributeList;
use OxidEsales\Eshop\Core\Config;
use OxidEsales\Eshop\Core\Registry;

class Export
{
    private const ATTRIBUTE_TO_EXPORT_PATH      = 'ffExportAttributes';
    private const ATTRIBUTE_TO_EXPORT_SEPARATE  = '0';
    private const ATTRIBUTE_TO_EXPORT_MULTI     = '1';
    private const ATTRIBUTE_TO_EXPORT_NUMERICAL = '2';

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
            return $config[$attribute->getFieldData('oxid')] == self::ATTRIBUTE_TO_EXPORT_MULTI;
        }));
    }

    public function getSingleFields(): array
    {
        $config = $this->getSelectedAttributesConfig();
        return array_values(array_filter($this->getAttributes(), function (ExportAttribute $attribute) use ($config): bool {
            return $config[$attribute->getFieldData('oxid')] == self::ATTRIBUTE_TO_EXPORT_SEPARATE;
        }));
    }

    public function getNumericalFields(): array
    {
        $config = $this->getSelectedAttributesConfig();
        return array_values(array_filter($this->getAttributes(), function (ExportAttribute $attribute) use ($config): bool {
            return $config[$attribute->getFieldData('oxid')] == self::ATTRIBUTE_TO_EXPORT_NUMERICAL;
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
