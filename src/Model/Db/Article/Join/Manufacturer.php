<?php

declare(strict_types=1);

namespace Omikron\FactFinder\Oxid\Model\Db\Article\Join;

use Omikron\FactFinder\Oxid\Contract\Db\JoinInterface;

class Manufacturer extends AbstractJoin implements JoinInterface
{
    /** @var string */
    private $manufacturerView;

    public function __construct()
    {
        parent::__construct();
        $this->manufacturerView = getViewName('oxmanufacturers');
    }

    public function getJoin(): array
    {
        return [
            $this->articleView => [
                'joinType' => 'left',
                'joinTable' => "{$this->manufacturerView }",
                'joinAlias' => "{$this->manufacturerView }",
                'joinCondition' => "{$this->manufacturerView}.oxid = {$this->articleView}.oxmanufacturerid"
            ]
        ];
    }
}
