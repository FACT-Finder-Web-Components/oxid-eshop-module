<?php

declare(strict_types=1);

namespace Omikron\FactFinder\Oxid\Model\Db\Article;

use Omikron\FactFinder\Oxid\Contract\Db\JoinFactoryInterface;
use Omikron\FactFinder\Oxid\Contract\Db\JoinInterface;
use Omikron\FactFinder\Oxid\Model\Db\Article\Join\ArticleExtend;
use Omikron\FactFinder\Oxid\Model\Db\Article\Join\Attributes;
use Omikron\FactFinder\Oxid\Model\Db\Article\Join\Categories;
use Omikron\FactFinder\Oxid\Model\Db\Article\Join\Composite;
use Omikron\FactFinder\Oxid\Model\Db\Article\Join\Manufacturer;
use Omikron\FactFinder\Oxid\Model\Db\Article\Join\Seo;

class JoinFactory implements JoinFactoryInterface
{
    public function create(): JoinInterface
    {
        return new Composite([
            oxNew(ArticleExtend::class),
            oxNew(Attributes::class),
            oxNew(Categories::class),
            oxNew(Manufacturer::class),
            oxNew(Seo::class)
        ]);
    }
}
