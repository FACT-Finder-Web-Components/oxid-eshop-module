<?php

namespace Omikron\FactFinder\Oxid\Contract\Db;

interface SelectInterface
{
    /**
     * Returns assoc array column name => select expression
     *
     * @return array
     */
    public function getFields(): array;
}
