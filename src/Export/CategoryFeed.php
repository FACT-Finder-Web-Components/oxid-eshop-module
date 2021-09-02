<?php


namespace Omikron\FactFinder\Oxid\Export;


use Omikron\FactFinder\Oxid\Export\Data\CategoryCollection;
use Omikron\FactFinder\Oxid\Export\Entity\DataProvider;
use Omikron\FactFinder\Oxid\Export\Field\BaseFieldInterface;
use Omikron\FactFinder\Oxid\Export\Field\Category\FieldInterface;
use Omikron\FactFinder\Oxid\Export\Stream\StreamInterface;

class CategoryFeed extends AbstractFeed
{
    /** @var FieldInterface[]  */
    protected $fields;

    public function __construct(BaseFieldInterface ...$fields)
    {
        $this->fields = $fields;
    }

    public function generate(StreamInterface $stream): void
    {
        var_dump($this->fields);die;
        $fields  = array_merge($this->getAdditionalFields(), $this->getConfigFields(), $this->fields);
        var_dump($fields);
        die;
        $columns = array_unique(array_merge($this->columns, array_map([$this, 'getFieldName'], $fields)));

        $stream->addEntity($columns);
        oxNew(Exporter::class)->exportEntities($stream, oxNew(DataProvider::class, ...$fields), $columns, new CategoryCollection());
    }

    public function getAdditionalFields(): array
    {
        return [];
    }

    public function setColumns(): AbstractFeed
    {
        $this->columns = [
            'ShopId',
            'LangId',
            'Id',
            'Name',
            'Pid',
            'Path',
            'Thumb',
            'Link'
        ];

        return $this;
    }
}
