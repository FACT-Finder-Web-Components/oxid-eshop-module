<?php

declare(strict_types=1);

namespace Omikron\FactFinder\Oxid\Model\Export\Entity\Article\Fields;

use Omikron\FactFinder\Oxid\Contract\Export\Entity\FieldModifierInterface;
use Omikron\FactFinder\Oxid\Model\Export\AbstractEntity;
use OxidEsales\Eshop\Application\Model\Category;

class CategoryPath implements FieldModifierInterface
{
    private $cachedPaths = [];

    public function getName(): string
    {
        return 'CategoryPath';
    }

    public function getValue(AbstractEntity $entity): string
    {
        $paths = [];
        if ($entity->getCategoryPath() == '') {
            return $entity->getCategoryPath();
        }

        foreach (explode(',', $entity->getCategoryPath()) as $pathId) {
            if (isset($this->cachedPaths[$pathId])) {
                $paths [] = $this->cachedPaths[$pathId];
            } else {
                $category = $this->getCategoryById($pathId);
                $path = [];
                while ($category->oxcategories__oxparentid->value != 'oxrootid') {
                    $path[] = $category->oxcategories__oxtitle->value;
                    $category = $this->getCategoryById($category->oxcategories__oxparentid->value);
                }
                if (!empty($path)) {
                    $this->cachedPaths[$pathId] = implode('/', array_reverse($path));
                    $paths[] = $this->cachedPaths[$pathId];
                }
            }
        }

        return implode('|', $paths);
    }

    private function getCategoryById(string $id): Category
    {
        $category = oxNew(Category::class);
        if (!$category->load($id)) {
            throw new \Exception('Could not load category for id:' . $id);
        }

        return $category;
    }
}
