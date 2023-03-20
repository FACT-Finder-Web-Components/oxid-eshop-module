<?php

declare(strict_types=1);

namespace Omikron\FactFinder\Oxid\Export;

use Omikron\FactFinder\Oxid\Export\Data\CategoryCollection;
use Omikron\FactFinder\Oxid\Export\Entity\DataProvider;
use Omikron\FactFinder\Oxid\Export\Field\Category\CategoryPath;
use Omikron\FactFinder\Oxid\Export\Field\Category\FieldInterface;
use Omikron\FactFinder\Oxid\Export\Field\Category\SourceField;
use Omikron\FactFinder\Oxid\Export\Stream\StreamInterface;
use OxidEsales\EshopCommunity\Internal\Container\ContainerFactory;
use Psr\Container\ContainerInterface;

class SuggestCategoryFeed extends AbstractFeed
{
    /** @var FieldInterface[] */
    protected $fields;

    /** @var ContainerInterface */
    protected $container;

    protected $columns = [
        'Id',
        'Name',
    ];

    public function __construct(FieldInterface ...$fields)
    {
        $this->fields = $fields;
        $this->container = ContainerFactory::getInstance()->getContainer();
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
//        'parentCategory',
//        'Deeplink',
        return [
            oxNew(CategoryPath::class),
            $this->container->get(SourceField::class),
        ];
    }

    protected function getFieldName(FieldInterface $field): string
    {
        return $field->getName();
    }
}
