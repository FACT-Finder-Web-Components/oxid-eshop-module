<?php

declare(strict_types=1);

namespace Omikron\FactFinder\Oxid\Export;

use Omikron\FactFinder\Oxid\Export\Entity\DataProvider;
use Omikron\FactFinder\Oxid\Export\Field\Brand;
use Omikron\FactFinder\Oxid\Export\Field\CategoryPath;
use Omikron\FactFinder\Oxid\Export\Stream\StreamInterface;

class Feed
{
    /** @var array */
    private $fields;

    /** @var array */
    private $columns;

    public function __construct(array $fields = [], array $columns = [])
    {
        $this->fields   = $fields;
        $this->fields[] = oxNew(Brand::class);
        $this->fields[] = oxNew(CategoryPath::class);

        $this->columns = array_merge([
            'ProductNumber',
            'Master',
            'Name',
            'Short',
            'Description',
            'Brand',
            'Price',
            'Deeplink',
            'ImageUrl',
            'CategoryPath',
        ], $columns);
    }

    public function generate(StreamInterface $stream): void
    {
        $stream->addEntity($this->columns);
        oxNew(Exporter::class)->exportEntities($stream, oxNew(DataProvider::class, ...$this->fields), $this->columns);
    }
}
