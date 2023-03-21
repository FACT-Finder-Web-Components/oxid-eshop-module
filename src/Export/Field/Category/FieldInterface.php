<?php

declare(strict_types=1);

namespace Omikron\FactFinder\Oxid\Export\Field\Category;

use OxidEsales\Eshop\Application\Model\Category;

interface FieldInterface
{
    public function getName(): string;

    public function getValue(Category $category, Category $parent): string;
}
