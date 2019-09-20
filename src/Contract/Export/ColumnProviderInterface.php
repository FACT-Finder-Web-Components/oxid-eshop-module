<?php

namespace Omikron\FactFinder\Oxid\Contract\Export;

interface ColumnProviderInterface
{
    /**
     * Returns field names as array
     *
     * @return array
     */
    public function getFields(): array;
}
