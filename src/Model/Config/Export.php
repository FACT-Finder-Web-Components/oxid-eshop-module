<?php

declare(strict_types=1);

namespace Omikron\FactFinder\Oxid\Model\Config;

use OxidEsales\Eshop\Application\Model\AttributeList;
use OxidEsales\Eshop\Core\Model\BaseModel;
use OxidEsales\Eshop\Core\Registry;

class Export
{
    private const ATTRIBUTES_TO_EXPORT_PATH = 'ffExportAttributes';

    /** @var BaseModel[] */
    private $attributes;

    public function getMultiAttributes(): array
    {
        return array_intersect_key($this->getAttributes(), array_filter($this->getConfigValue()));
    }

    public function getSingleFields(): array
    {
        return array_intersect_key($this->getAttributes(), array_filter($this->getConfigValue(), function ($value) {
            return !$value;
        }));
    }

    public function getConfigValue(): array
    {
        return Registry::getConfig()->getConfigParam(self::ATTRIBUTES_TO_EXPORT_PATH) ?: [];
    }

    private function getAttributes(): array
    {
        $this->attributes = $this->attributes ?? oxNew(AttributeList::class)->getList()->getArray();
        return array_map([$this, 'getAttributeName'], $this->attributes);
    }

    private function getAttributeName(BaseModel $attribute): string
    {
        return $attribute->getFieldData('oxtitle');
    }
}
