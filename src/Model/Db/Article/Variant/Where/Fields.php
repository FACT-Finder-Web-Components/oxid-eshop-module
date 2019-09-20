<?php

declare(strict_types=1);

namespace Omikron\FactFinder\Oxid\Model\Db\Article\Variant\Where;

use Omikron\FactFinder\Oxid\Contract\Db\WhereInterface;
use Omikron\FactFinder\Oxid\Model\Db\Article\Where\Fields as ParentFields;

class Fields extends ParentFields implements WhereInterface
{
    /** @var string */
    private $parentId;

    public function __construct(string $parentId = '')
    {
        parent::__construct();
        $this->parentId = $parentId;
    }

    public function getClause(): array
    {
        return [
                'variants' => $this->parentId != '' ? "{$this->articleView}.oxparentid = '{$this->parentId}'" : "{$this->articleView}.oxparentid != ''"
            ] + parent::getClause();
    }
}
