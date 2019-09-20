<?php

declare(strict_types=1);

namespace Omikron\FactFinder\Oxid\Model\Db\Article\Variant\Join;

use Omikron\FactFinder\Oxid\Contract\Db\JoinInterface;
use Omikron\FactFinder\Oxid\Model\Db\Article\Join\AbstractJoin;

class ParentArticle extends AbstractJoin implements JoinInterface
{
    const PARENT_ARTICLE_ALIAS = 'parentoxarticles';

    public function getJoin(): array
    {
        $parentAlias = self::PARENT_ARTICLE_ALIAS;

        return [
            $this->articleView => [
                'joinType' => 'left',
                'joinTable' => "{$this->articleView}",
                'joinAlias' => $parentAlias,
                'joinCondition' => "{$this->articleView}.oxparentid = $parentAlias.oxid"
            ]
        ];
    }
}
