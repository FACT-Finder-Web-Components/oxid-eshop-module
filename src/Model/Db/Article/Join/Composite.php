<?php

declare(strict_types=1);

namespace Omikron\FactFinder\Oxid\Model\Db\Article\Join;

use Omikron\FactFinder\Oxid\Contract\Db\JoinInterface;

class Composite implements JoinInterface
{
    private $joins;

    public function __construct(array $joins)
    {
        $this->joins = $joins;
    }

    public function getJoin(): array
    {
        return array_reduce($this->joins, function (array $mergedJoins, JoinInterface $join): array {
            foreach ($join->getJoin() as $alias => $params) {
                $mergedJoins[$alias][] = $params;
            }
            return $mergedJoins;
        }, []);
    }
}
