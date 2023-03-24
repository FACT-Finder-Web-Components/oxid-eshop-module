<?php

declare(strict_types=1);

namespace Omikron\FactFinder\Oxid\Export\Field\Category;

use OxidEsales\Eshop\Application\Model\Category;

class ParentCategory implements FieldInterface
{
    public function getName(): string
    {
        return 'ParentCategory';
    }

    public function getValue(Category $category, Category $parent): string
    {
        return $this->getPath($category);
    }

    protected function getPath(Category $category, string $glue = '/'): string
    {
        if ($category->isTopCategory()) {
            return $category->getTitle();
        }

        $path = [];

        while ($category->isTopCategory() === false) {
            $category = $category->getParentCategory();
            $path[]   = $category->getTitle();
        }

        return implode($glue, array_reverse($path));
    }
}
