<?php

declare(strict_types=1);

namespace Omikron\FactFinder\Oxid\Model\Db\Article;

use Omikron\FactFinder\Oxid\Contract\Db\WhereFactoryInterface;
use Omikron\FactFinder\Oxid\Contract\Db\WhereInterface;
use Omikron\FactFinder\Oxid\Model\Db\Article\Where\Composite;
use Omikron\FactFinder\Oxid\Model\Db\Article\Where\Fields;

class WhereFactory implements WhereFactoryInterface
{
    public function create(): WhereInterface
    {
        return new Composite([
            oxNew(Fields::class),
        ]);
    }
}
