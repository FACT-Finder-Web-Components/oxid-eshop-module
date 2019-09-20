<?php

declare(strict_types=1);

namespace Omikron\FactFinder\Oxid\Model\Db\Article\Variant;

use Omikron\FactFinder\Oxid\Model\Db\Article\Variant\JoinFactory as VariantJoinFactory;
use Omikron\FactFinder\Oxid\Model\Db\Article\Variant\SelectFactory as VariantSelectFactory;
use Omikron\FactFinder\Oxid\Model\Db\Article\Variant\Where\Fields;
use Omikron\FactFinder\Oxid\Model\Db\Records;

class RecordsFactory
{
    public function create(string $parentId = '')
    {
        $articlesView = getViewName('oxarticles');
        return oxNew(
            Records::class,
            oxNew(VariantSelectFactory::class)->create(),
            $articlesView,
            oxNew(VariantJoinFactory::class)->create(),
            oxNew(Fields::class, $parentId),
            "{$articlesView}.oxartnum"
        );
    }
}
