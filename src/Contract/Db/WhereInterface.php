<?php

namespace Omikron\FactFinder\Oxid\Contract\Db;

interface WhereInterface
{
    /**
     * Returns assoc array column name => where expression
     *
     * @return array
     */
    public function getClause(): array;
}
