<?php

declare(strict_types=1);

namespace Omikron\FactFinder\Oxid\Export\Field\Category;

use Omikron\FactFinder\Oxid\Export\Filter\TextFilter;
use OxidEsales\Eshop\Application\Model\Category;

class CategoryPath implements FieldInterface
{
    public function __construct()
    {
        $this->filter = oxNew(TextFilter::class);
    }

    public function getName(): string
    {
        return 'CategoryPath';
    }

    public function getValue(Category $category, Category $parent): string
    {
        return $this->getPath($category);
    }

    protected function getPath(Category $category, string $glue = '/'): string
    {
        $path = [
            rawurlencode($this->filter->filterValue($category->getTitle()))
        ];

        while ($category->isTopCategory() === false) {
            $category = $category->getParentCategory();
            $path[]   = rawurlencode($this->filter->filterValue($category->getTitle()));
        }

        return implode($glue, array_reverse($path));
    }
}
