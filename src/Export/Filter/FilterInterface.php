<?php

namespace Omikron\FactFinder\Oxid\Export\Filter;

interface FilterInterface
{
    public function filterValue(string $value): string;
}
