<?php

declare(strict_types=1);

namespace Omikron\FactFinder\Oxid\Export\Field;

use Omikron\FactFinder\Oxid\Export\Filter\TextFilter;
use OxidEsales\Eshop\Application\Model\Article;
use OxidEsales\Eshop\Application\Model\Category;

class CategoryPath implements FieldInterface
{
    /** @var Category[] */
    protected $categories = [];

    /** @var TextFilter */
    protected $filter;

    public function __construct()
    {
        $this->filter = oxNew(TextFilter::class);
    }

    public function getName(): string
    {
        return 'CategoryPath';
    }

    public function getValue(Article $article, Article $parent): string
    {
        return implode('|', array_map([$this, 'getPath'], $parent->getCategoryIds()));
    }

    protected function getPath(string $id, string $glue = '/'): string
    {
        $path = [];
        while ($id !== 'oxrootid') {
            $category = $this->getCategory($id);
            $id       = $category->getFieldData('oxparentid');
            $path[]   = rawurlencode($this->filter->filterValue($category->getTitle()));
        }
        return implode($glue, array_reverse($path));
    }

    protected function getCategory(string $id): Category
    {
        $category = $this->categories[$id] = $this->categories[$id] ?? oxNew(Category::class);
        if (!$category->isLoaded()) $category->load($id);
        return $category;
    }
}
