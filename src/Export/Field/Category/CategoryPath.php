<?php

declare(strict_types=1);

namespace Omikron\FactFinder\Oxid\Export\Field\Category;

use OxidEsales\Eshop\Application\Model\Category;

class CategoryPath implements FieldInterface
{
    public function getName(): string
    {
        return 'CategoryPath';
    }

    public function getValue(Category $category, Category $parent): string // phpcs:ignore
    {
        return '';
    }
}
