<?php

declare(strict_types=1);

namespace Omikron\FactFinder\Oxid\Export\Entity;

use Omikron\FactFinder\Oxid\Export\Field\Category\FieldInterface;
use OxidEsales\Eshop\Application\Model\Category;

class CategoryEntity implements DataProviderInterface, ExportEntityInterface
{
    /** @var Category */
    protected $category;

    /** @var Category */
    protected $parent;

    /** @var FieldInterface[] */
    protected $fields;

    /**
     * CategoryEntity constructor.
     *
     * @param Category         $article
     * @param Category         $parent
     * @param FieldInterface[] $fields
     */
    public function __construct(Category $article, Category $parent, array $fields)
    {
        $this->category = $article;
        $this->parent   = $parent;
        $this->fields   = $fields;
    }

    public function getEntities(): iterable
    {
        yield $this;
        foreach ($this->category->getSubCats() ?? [] as $subCat) {
            yield new static($subCat, $this->category, $this->fields);
        }
    }

    public function toArray(): array
    {
        $data = [
            'Id'           => $this->category->getFieldData('oxid'),
            'ShopId'       => $this->category->getFieldData('oxshopid'),
            'ExternalLink' => $this->category->getFieldData('oxextlink'),
            'ParentId'     => $this->category->getFieldData('oxparentid'),
            'RootId'       => $this->category->getFieldData('oxrootid'),
            'Name'         => $this->category->getFieldData('oxtitle'),
            'ImageUrl'     => $this->category->getPictureUrl(),
            'Description'  => $this->category->getFieldData('oxdesc'),
        ];

        return array_reduce($this->fields, function (array $result, FieldInterface $field): array {
            return [$field->getName() => $field->getValue($this->category, $this->parent)] + $result;
        }, $data);
    }
}
