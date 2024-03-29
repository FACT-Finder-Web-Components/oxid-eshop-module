<?php

declare(strict_types=1);

namespace Omikron\FactFinder\Oxid\Export\Field\Category;

use OxidEsales\Eshop\Application\Model\Category;

class SourceField implements FieldInterface
{
    /** @var string */
    private $categoryPathFieldName;

    public function __construct(string $categoryPathFieldName)
    {
        $this->categoryPathFieldName = $categoryPathFieldName;
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
