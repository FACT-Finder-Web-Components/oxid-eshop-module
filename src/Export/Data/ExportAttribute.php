<?php

declare(strict_types=1);

namespace Omikron\FactFinder\Oxid\Export\Data;

use Omikron\FactFinder\Oxid\Model\Config\Export as ExportConfig;
use OxidEsales\Eshop\Application\Model\Attribute;
use OxidEsales\Eshop\Core\Registry;

class ExportAttribute extends Attribute
{
    public function getSqlActiveSnippet($blForceCoreTable = null)
    {
        /** @var ExportConfig $exportConfig */
        $exportConfig   = oxNew(ExportConfig::class);
        $selectedIds    = implode("','", array_keys($exportConfig->getSelectedAttributesConfig()));
        $view = $this->getViewName($blForceCoreTable);

        return parent::getSqlActiveSnippet($blForceCoreTable) . "`{$view}`.`oxid` in ('{$selectedIds}')";
    }
}
