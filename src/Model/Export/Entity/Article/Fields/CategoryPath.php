<?php

declare(strict_types=1);

namespace Omikron\FactFinder\Oxid\Model\Export\Entity\Article\Fields;

use Omikron\FactFinder\Oxid\Contract\Export\Entity\FieldModifierInterface;
use Omikron\FactFinder\Oxid\Model\Export\AbstractEntity;
use OxidEsales\Eshop\Application\Model\Category;

class CategoryPath implements FieldModifierInterface
{
    /** @var string[] */
    private $cachedPaths = [];

    public function getName(): string
    {
        return 'CategoryPath';
    }

    public function getValue(AbstractEntity $entity): string
    {
        if (!$entity->getCategoryPath()) {
            return (string) $entity->getCategoryPath();
        }

        $paths = [];
        foreach (explode(',', $entity->getCategoryPath()) as $pathId) {
            if (isset($this->cachedPaths[$pathId])) {
                $paths[] = $this->cachedPaths[$pathId];
            } else {
                $path = [];
                while ($category = $this->getCategoryById($pathId)) {
                    $path[] = rawurlencode($category->getTitle());
                    if (!$category->getParentCategory()) {
                        break;
                    }
                    $pathId = $category->getParentCategory()->getId();
                }

                if (!empty($path)) {
                    $this->cachedPaths[$pathId] = $paths[] = implode('/', array_reverse($path));
                }
            }
        }

        return $paths ? '|' . implode('|', $paths) . '|' : '';
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
