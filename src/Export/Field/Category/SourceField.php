<?php

declare(strict_types=1);

namespace Omikron\FactFinder\Oxid\Export\Field\Category;

use OxidEsales\Eshop\Application\Model\Category;

class SourceField implements FieldInterface
{
    public function __construct(private readonly string $categoryPathFieldName)
    {
    }

    public function getName(): string
    {
        return 'SourceField';
    }

    public function getValue(Category $category, Category $parent): string
    {
        return $this->categoryPathFieldName;
    }
}
