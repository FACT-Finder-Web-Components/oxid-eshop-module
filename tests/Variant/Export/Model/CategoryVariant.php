<?php

declare(strict_types=1);

namespace FactFinderTests\Variant\Export\Model;

use OxidEsales\Eshop\Application\Model\Category;

class CategoryVariant extends Category
{
    public function __construct($params = [])
    {
        foreach ($params as $sParam => $mValue) {
            $this->$sParam = $mValue;
        }

        parent::__construct();
    }
}
