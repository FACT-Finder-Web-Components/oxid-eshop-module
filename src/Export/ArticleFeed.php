<?php

declare(strict_types=1);

namespace Omikron\FactFinder\Oxid\Export;

use Omikron\FactFinder\Oxid\Export\Data\ArticleCollection;
use Omikron\FactFinder\Oxid\Export\Entity\BatchDataProvider;
use Omikron\FactFinder\Oxid\Export\Entity\DataProvider;
use Omikron\FactFinder\Oxid\Export\Field\Article\FieldInterface;
use Omikron\FactFinder\Oxid\Export\Field\Attribute as AttributeField;
use Omikron\FactFinder\Oxid\Export\Field\Brand;
use Omikron\FactFinder\Oxid\Export\Field\CategoryPath;
use Omikron\FactFinder\Oxid\Export\Field\FilterAttributes;
use Omikron\FactFinder\Oxid\Export\Field\Keywords;
use Omikron\FactFinder\Oxid\Export\Stream\StreamInterface;
use Omikron\FactFinder\Oxid\Model\Config\Export as ExportConfig;

class ArticleFeed extends AbstractFeed
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
        $fields  = array_merge($this->getAdditionalFields(), $this->getConfigFields(), $this->fields);
        $columns = array_unique(array_merge($this->columns, array_map([$this, 'getFieldName'], $fields)));

        $stream->addEntity($columns);
        oxNew(Exporter::class)->exportEntities($stream, oxNew(DataProvider::class, oxNew(ArticleCollection::class), ...$fields), $columns);
    }

    public function generateBatch(
        StreamInterface $stream,
        int $offset,
        int $limit,
        bool $withHeader,
    ): int {
        $fields = array_merge(
            $this->getAdditionalFields(),
            $this->getConfigFields(),
            $this->fields
        );

        $columns = array_unique(
            array_merge(
                $this->columns,
                array_map([$this, 'getFieldName'], $fields)
            )
        );

        if ($withHeader) {
            $stream->addEntity($columns);
        }

        return oxNew(Exporter::class)->exportEntities(
            $stream,
            oxNew(
                BatchDataProvider::class,
                oxNew(ArticleCollection::class),
                $offset,
                $limit,
                ...$fields
            ),
            $columns
        );
    }

    protected function getAdditionalFields(): array
    {
        return [
            oxNew(Brand::class),
            oxNew(CategoryPath::class),
            oxNew(FilterAttributes::class),
            oxNew(Keywords::class),
        ];
    }

    protected function getConfigFields(): array
    {
        return array_map(fn (string $attribute): FieldInterface => oxNew(AttributeField::class, $attribute), array_values(oxNew(ExportConfig::class)->getSingleFields()));
    }

    protected function getFieldName(FieldInterface $field): string
    {
        return $field->getName();
    }
}
