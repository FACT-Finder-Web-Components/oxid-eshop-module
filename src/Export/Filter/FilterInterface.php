<?php

declare(strict_types=1);

namespace Omikron\FactFinder\Oxid\Export\Filter;

interface FilterInterface
{
    public function filterValue(string $value): string;
}
