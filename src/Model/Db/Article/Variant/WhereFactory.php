<?php

declare(strict_types=1);

namespace Omikron\FactFinder\Oxid\Model\Db\Article\Variant;

use Omikron\FactFinder\Oxid\Contract\Db\WhereFactoryInterface;
use Omikron\FactFinder\Oxid\Contract\Db\WhereInterface;
use Omikron\FactFinder\Oxid\Model\Db\Article\Variant\Where\Fields as VariantFields;
use Omikron\FactFinder\Oxid\Model\Db\Article\Where\Composite;

class WhereFactory implements WhereFactoryInterface
{
    public function create(): WhereInterface
    {
        return new Composite([
            oxNew(VariantFields::class),
        ]);
    }
}
