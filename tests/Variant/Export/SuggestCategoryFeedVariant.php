<?php

declare(strict_types=1);

namespace Tests\Variant\Export;

use Omikron\FactFinder\Oxid\Export\Field\Category\FieldInterface;
use Omikron\FactFinder\Oxid\Export\SuggestCategoryFeed;

class SuggestCategoryFeedVariant extends SuggestCategoryFeed
{
    /**
     * @return array|FieldInterface[]
     */
    public function getFields(): array
    {
        return array_merge($this->getAdditionalFields(), $this->fields);
    }

    /**
     * @return array|FieldInterface[]
     */
    public function getColumns(): array
    {
        return array_unique(
            array_merge(
                $this->columns,
                array_map(
                    [$this, 'getFieldName'],
                    $this->getFields()
                )
            )
        );
    }
}
