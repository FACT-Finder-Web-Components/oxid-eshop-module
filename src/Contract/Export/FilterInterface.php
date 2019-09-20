<?php

namespace Omikron\FactFinder\Oxid\Contract\Export;

interface FilterInterface
{
    public function filterValue(string $value): string;
}
