<?php

declare(strict_types=1);

namespace Omikron\FactFinder\Oxid\Model\Db\Article\Where;

use Omikron\FactFinder\Oxid\Contract\Db\WhereInterface;

class Composite implements WhereInterface
{
    private $whereArray;

    public function __construct(array $whereArray)
    {
        $this->whereArray = $whereArray;
    }

    public function getClause(): array
    {
        return array_reduce($this->whereArray, function (array $wheres, WhereInterface $where): array {
            return array_merge($wheres, $where->getClause());
        }, []);
    }
}
