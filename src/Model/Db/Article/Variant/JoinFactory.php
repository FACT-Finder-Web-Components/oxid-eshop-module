<?php

declare(strict_types=1);

namespace Omikron\FactFinder\Oxid\Model\Db\Article\Variant;

use Omikron\FactFinder\Oxid\Contract\Db\JoinFactoryInterface;
use Omikron\FactFinder\Oxid\Contract\Db\JoinInterface;
use Omikron\FactFinder\Oxid\Model\Db\Article\Join\Attributes;
use Omikron\FactFinder\Oxid\Model\Db\Article\Join\Composite;
use Omikron\FactFinder\Oxid\Model\Db\Article\Join\Manufacturer;
use Omikron\FactFinder\Oxid\Model\Db\Article\Variant\Join\ArticleExtend as VariantArticleExtend;
use Omikron\FactFinder\Oxid\Model\Db\Article\Variant\Join\Categories as VariantCategories;
use Omikron\FactFinder\Oxid\Model\Db\Article\Variant\Join\ParentArticle;
use Omikron\FactFinder\Oxid\Model\Db\Article\Variant\Join\Seo as VariantSeo;

class JoinFactory implements JoinFactoryInterface
{
    public function create(): JoinInterface
    {
        return new Composite([
            oxNew(ParentArticle::class),
            oxNew(Attributes::class),
            oxNew(VariantArticleExtend::class),
            oxNew(VariantCategories::class),
            oxNew(Manufacturer::class),
            oxNew(VariantSeo::class)
        ]);
    }
}
