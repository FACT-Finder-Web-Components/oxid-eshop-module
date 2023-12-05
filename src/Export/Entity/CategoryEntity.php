<?php

declare(strict_types=1);

namespace Omikron\FactFinder\Oxid\Export\Entity;

use Omikron\FactFinder\Oxid\Export\Field\Category\FieldInterface;
use OxidEsales\Eshop\Application\Model\Category;

class CategoryEntity implements DataProviderInterface, ExportEntityInterface
{
    public function __construct(
        protected readonly Category $category,
        protected readonly Category $parent,
        protected readonly array $fields
    ) {
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
            'Id'              => $this->category->getFieldData('oxid'),
            'ShopId'          => $this->category->getFieldData('oxshopid'),
            'ExternalLink'    => $this->category->getFieldData('oxextlink'),
            'ParentId'        => $this->category->getFieldData('oxparentid'),
            'RootId'          => $this->category->getFieldData('oxrootid'),
            'Name'            => $this->category->getFieldData('oxtitle'),
            'ImageUrl'        => $this->category->getPictureUrl(),
            'Description'     => $this->category->getFieldData('oxdesc'),
            'LongDescription' => $this->category->getFieldData('oxlongdesc'),
        ];

        return array_reduce($this->fields, function (array $result, FieldInterface $field): array {
            return [$field->getName() => $field->getValue($this->category, $this->parent)] + $result;
        }, $data);
    }
}
