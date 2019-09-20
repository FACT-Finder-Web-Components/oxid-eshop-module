<?php

declare(strict_types=1);

namespace Omikron\FactFinder\Oxid\Model\Db\Article\Select;

use Omikron\FactFinder\Oxid\Contract\Db\SelectInterface;

class Composite implements SelectInterface
{
    private $selects;

    public function __construct(array $selects)
    {
        $this->selects = $selects;
    }

    public function getFields(): array
    {
        return array_reduce($this->selects, function (array $mergedFields, SelectInterface $select): array {
            return array_merge($mergedFields, $select->getFields());
        }, []);
    }
}
