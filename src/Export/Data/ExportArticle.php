<?php

declare(strict_types=1);

namespace Omikron\FactFinder\Oxid\Export\Data;

use OxidEsales\Eshop\Application\Model\Article;

class ExportArticle extends Article
{
    public function getSqlActiveSnippet($blForceCoreTable = null)
    {
        $view = $this->getViewName($blForceCoreTable);
        return parent::getSqlActiveSnippet($blForceCoreTable) . " AND (`{$view}`.`oxparentid` = '')";
    }
}
