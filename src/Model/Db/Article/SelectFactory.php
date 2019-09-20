<?php

declare(strict_types=1);

namespace Omikron\FactFinder\Oxid\Model\Db\Article;

use Omikron\FactFinder\Oxid\Contract\Db\SelectFactoryInterface;
use Omikron\FactFinder\Oxid\Contract\Db\SelectInterface;
use Omikron\FactFinder\Oxid\Model\Db\Article\Select\Attributes;
use Omikron\FactFinder\Oxid\Model\Db\Article\Select\Base;
use Omikron\FactFinder\Oxid\Model\Db\Article\Select\Composite;
use Omikron\FactFinder\Oxid\Model\Db\Article\Select\Price;

class SelectFactory implements SelectFactoryInterface
{
    public function create(): SelectInterface
    {
        return new Composite([
                oxNew(Base::class),
                oxNew(Price::class),
                oxNew(Attributes::class),
            ]);
    }
}
