<?php

declare(strict_types=1);

namespace Omikron\FactFinder\Oxid\Export\Filter;

class ExtendedTextFilter extends TextFilter
{
    private const FORBIDDEN_CHARS = '/[|#=]/';

    public function filterValue(string $value): string
    {
        return trim(preg_replace(self::FORBIDDEN_CHARS, ' ', parent::filterValue($value)));
    }
}
