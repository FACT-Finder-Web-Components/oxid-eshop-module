<?php


namespace Omikron\FactFinder\Oxid\Export\Data;

use OxidEsales\Eshop\Core\Model\ListModel;

interface CollectionInterface extends \IteratorAggregate
{
    public function getBatch(int $from): ListModel;
}
