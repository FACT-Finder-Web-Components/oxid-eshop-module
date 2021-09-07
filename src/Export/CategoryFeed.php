<?php


namespace Omikron\FactFinder\Oxid\Export;


use Omikron\FactFinder\Oxid\Export\Data\CategoryCollection;
use Omikron\FactFinder\Oxid\Export\Entity\DataProvider;
use Omikron\FactFinder\Oxid\Export\Field\BaseFieldInterface;
use Omikron\FactFinder\Oxid\Export\Field\BaseFieldInterface as FieldInterface;
use Omikron\FactFinder\Oxid\Export\Stream\StreamInterface;

class CategoryFeed extends AbstractFeed
{
    /** @var FieldInterface[]  */
    protected $fields;

    protected $columns = [
            'Id',
            'ParentId',
            'RootId',
            'Name',
            'ImageUrl',
        ];

    public function __construct(BaseFieldInterface ...$fields)
    {
        $this->fields = $fields;
    }

    public function getName(): string
    {
        // TODO: Implement getName() method.
    }


    public function generate(StreamInterface $stream): void
    {
        $fields  = array_merge($this->getAdditionalFields(), $this->fields);
        $columns = array_unique(array_merge($this->columns, array_map([$this, 'getFieldName'], $fields)));

        $stream->addEntity($columns);
        oxNew(Exporter::class)->exportEntities($stream, oxNew(DataProvider::class, ...$fields), $columns, oxNew(CategoryCollection::class));
    }

    public function getAdditionalFields(): array
    {
        return [];
    }

    protected function getFieldName(FieldInterface $field): string
    {
        return parent::getFieldName($field);
    }
}
