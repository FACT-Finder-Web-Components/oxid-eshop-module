<?php

declare(strict_types=1);

namespace Omikron\FactFinder\Oxid\Model\Db\Article\Join;

use Omikron\FactFinder\Oxid\Contract\Db\JoinInterface;

class Attributes extends AbstractJoin implements JoinInterface
{
    /** @var string */
    protected $attributeView;

    public function getJoin(): array
    {
        return [
            $this->articleView => [
                'joinType' => 'left',
                'joinTable' => "{$this->object2AttributeView}",
                'joinAlias' => "{$this->object2AttributeView }",
                'joinCondition' => "{$this->object2AttributeView}.oxobjectid = {$this->articleView}.oxid"
            ],
            $this->object2AttributeView => [
                'joinType' => 'left',
                'joinTable' => "{$this->attributeView}",
                'joinAlias' => "{$this->attributeView }",
                'joinCondition' => "{$this->object2AttributeView}.oxattrid = {$this->attributeView}.oxid"
            ],
        ];
    }
}
