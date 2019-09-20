<?php

declare(strict_types=1);

namespace Omikron\FactFinder\Oxid\Model\Db\Article\Select;

use Omikron\FactFinder\Oxid\Contract\Db\SelectInterface;

class Base implements SelectInterface
{
    /** @var  string */
    protected $articleView;

    /** @var  string */
    private $manufacturerView;

    /** @var string */
    private $object2CategoryView;

    /** @var string */
    private $articleExtendView;

    /** @var string */
    private $seoTable = 'oxseo';

    public function __construct()
    {
        $this->articleView = getViewName('oxarticles');
        $this->object2CategoryView = getViewName('oxobject2category');
        $this->manufacturerView = getViewName('oxmanufacturers');
        $this->articleExtendView = getViewName('oxartextends');
    }

    public function getFields(): array
    {
        return [
            'Master' => "{$this->articleView}.oxid as Master",
            'Name' => "{$this->articleView}.oxtitle as Name",
            'ImageUrl' => "{$this->articleView}.oxpic1  as ImageUrl",
            'Short' => "{$this->articleView}.oxshortdesc as Short",
            'ProductNumber' => "{$this->articleView}.oxartnum as ProductNumber",
            'Description' => "{$this->articleExtendView}.oxlongdesc as Description",
            'ArticleUrl' => "{$this->seoTable}.oxseourl as ArticleUrl",
            'Brand' => "{$this->manufacturerView}.oxtitle as Manufacturer",
            'Availability' => "if ({$this->articleView}.oxvarcount > 0, 1, 0) as Availability",
            'OxidId' => "{$this->articleView}.oxid as OxidId",
            'CategoryPath' => "GROUP_CONCAT(DISTINCT {$this->object2CategoryView}.oxcatnid SEPARATOR ',') as CategoryPath",
        ];
    }
}
