<?php

namespace Omikron\FactFinder\Oxid\Contract\Db;

interface JoinInterface
{
    /**
     * Returns assoc array
     * [
     *      'fromAlias' => [
     *          'joinType' => 'left',
     *          'joinTable' => 'tablename'
     *          'joinAlias' => 'alias',
     *          'joinCondition' => 'alias.field = fromAlias.field';
 *          ]
     * ]
     *
     * @return array
     */
    public function getJoin(): array;
}
