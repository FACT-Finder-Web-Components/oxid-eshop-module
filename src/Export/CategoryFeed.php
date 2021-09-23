<?php

declare(strict_types=1);

namespace Omikron\FactFinder\Oxid\Export;

use Omikron\FactFinder\Oxid\Export\Data\CategoryCollection;
use Omikron\FactFinder\Oxid\Export\Entity\DataProvider;
use Omikron\FactFinder\Oxid\Export\Field\FieldInterface;
use Omikron\FactFinder\Oxid\Export\Stream\StreamInterface;

class CategoryFeed extends AbstractFeed
{
    /** @var FieldInterface[] */
    protected $fields;

    protected $columns = [
        'Id',
        'ShopId',
        'ExternalLink',
        'ParentId',
        'RootId',
        'Name',
        'ImageUrl',
        'Description',
    ];

    public function __construct(FieldInterface ...$fields)
    {
        $this->fields = $fields;
    }

    public function generate(StreamInterface $stream): void
    {
        $fields  = array_merge($this->getAdditionalFields(), $this->fields);
        $columns = array_unique(array_merge($this->columns, array_map([$this, 'getFieldName'], $fields)));

        $stream->addEntity($columns);
        oxNew(Exporter::class)->exportEntities($stream, oxNew(DataProvider::class, oxNew(CategoryCollection::class), ...$fields), $columns);
    }

    protected function getAdditionalFields(): array
    {
        return [];
    }
}
