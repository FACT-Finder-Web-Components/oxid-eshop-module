<?php

declare(strict_types=1);

namespace Omikron\FactFinder\Oxid\Model\Db\Article\Select;

use Omikron\FactFinder\Oxid\Contract\Db\SelectInterface;

class Attributes implements SelectInterface
{
    /** @var string */
    protected $attributeView;

    /** @var string */
    private $object2attributeView;

    public function __construct()
    {
        $this->attributeView        = getViewName('oxattribute');
        $this->object2attributeView = getViewName('oxobject2attribute');
    }

    public function getFields(): array
    {
        return [
            'Attributes' => "GROUP_CONCAT(DISTINCT CONCAT({$this->attributeView}.oxtitle, '=', {$this->object2attributeView}.oxvalue) SEPARATOR '|')",
        ];
    }
}
