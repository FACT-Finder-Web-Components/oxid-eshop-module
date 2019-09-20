<?php

declare(strict_types=1);

namespace Omikron\FactFinder\Oxid\Model\Db\Article\Variant\Join;

use Omikron\FactFinder\Oxid\Contract\Db\JoinInterface;
use Omikron\FactFinder\Oxid\Model\Db\Article\Join\AbstractJoin;

class ArticleExtend extends AbstractJoin implements JoinInterface
{
    public function getJoin(): array
    {
        return [
            $this->articleView  => [
                'joinType' => 'left',
                'joinTable' => "{$this->articleExtendView}",
                'joinAlias' => "{$this->articleExtendView }",
                'joinCondition' => "{$this->articleExtendView}.oxid = {$this->articleView}.oxparentid"
            ],
        ];
    }
}
