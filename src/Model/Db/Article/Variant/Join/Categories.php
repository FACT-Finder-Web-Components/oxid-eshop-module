<?php

declare(strict_types=1);

namespace Omikron\FactFinder\Oxid\Model\Db\Article\Variant\Join;

use Omikron\FactFinder\Oxid\Contract\Db\JoinInterface;
use Omikron\FactFinder\Oxid\Model\Db\Article\Join\Categories as ParentCategories;

class Categories extends ParentCategories implements JoinInterface
{
    public function getJoin(): array
    {
        return [
                $this->articleView => [
                    'joinType' => 'left',
                    'joinTable' => "{$this->object2CategoryView}",
                    'joinAlias' => "{$this->object2CategoryView }",
                    'joinCondition' => "{$this->object2CategoryView}.oxobjectid = {$this->articleView}.oxparentid"
                ]
            ] + parent::getJoin();
    }
}
