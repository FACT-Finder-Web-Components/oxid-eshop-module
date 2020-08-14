<?php

declare(strict_types=1);

namespace Omikron\FactFinder\Oxid\Export;

use Omikron\FactFinder\Oxid\Export\Entity\DataProvider;
use Omikron\FactFinder\Oxid\Export\Field\Brand;
use Omikron\FactFinder\Oxid\Export\Field\CategoryPath;
use Omikron\FactFinder\Oxid\Export\Field\FieldInterface;
use Omikron\FactFinder\Oxid\Export\Stream\StreamInterface;

class Feed
{
    /** @var FieldInterface[] */
    protected $fields;

    /** @var string[] */
    protected $columns = [
        'ProductNumber',
        'Master',
        'Name',
        'Short',
        'Description',
        'Price',
        'Deeplink',
        'ImageUrl',
    ];

    public function __construct(FieldInterface ...$fields)
    {
        $this->fields = $fields;
    }

    public function generate(StreamInterface $stream): void
    {
        $fields  = array_merge([oxNew(Brand::class), oxNew(CategoryPath::class)], $this->fields);
        $columns = array_unique(array_merge($this->columns, array_map([$this, 'getFieldName'], $fields)));

        $stream->addEntity($columns);
        oxNew(Exporter::class)->exportEntities($stream, oxNew(DataProvider::class, ...$fields), $columns);
    }

    protected function getFieldName(FieldInterface $field): string
    {
        return $field->getName();
    }
}
