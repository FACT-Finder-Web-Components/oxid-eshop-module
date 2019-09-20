<?php

declare(strict_types=1);

namespace Omikron\FactFinder\Oxid\Model\Db\Article;

use Omikron\FactFinder\Oxid\Model\Db\Records;

class RecordsFactory
{
    public function create()
    {
        $articlesView = getViewName('oxarticles');
        return oxNew(
            Records::class,
            oxNew(SelectFactory::class)->create(),
            $articlesView,
            oxNew(JoinFactory::class)->create(),
            oxNew(WhereFactory::class)->create(),
            "{$articlesView}.oxartnum"
        );
    }
}
