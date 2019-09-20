<?php

declare(strict_types=1);

namespace Omikron\FactFinder\Oxid\Model\Db\Article\Join;

use Omikron\FactFinder\Oxid\Contract\Db\JoinInterface;

class Categories extends AbstractJoin implements JoinInterface
{
    /** @var string */
    protected $categoryView;

    public function __construct()
    {
        parent::__construct();
        $this->categoryView = getViewName('oxcategories');
    }

    public function getJoin(): array
    {
        return [
            $this->articleView => [
                'joinType' => 'left',
                'joinTable' => "{$this->object2CategoryView}",
                'joinAlias' => "{$this->object2CategoryView }",
                'joinCondition' => "{$this->object2CategoryView}.oxobjectid = {$this->articleView}.oxid"
            ],
            $this->object2CategoryView => [
                'joinType' => 'left',
                'joinTable' => "{$this->categoryView}",
                'joinAlias' => "{$this->categoryView }",
                'joinCondition' => "{$this->object2CategoryView}.oxcatnid = {$this->categoryView}.oxid"
            ],
        ];
    }
}
