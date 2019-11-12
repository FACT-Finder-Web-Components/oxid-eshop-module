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
        $this->articleView         = getViewName('oxarticles');
        $this->object2CategoryView = getViewName('oxobject2category');
        $this->manufacturerView    = getViewName('oxmanufacturers');
        $this->articleExtendView   = getViewName('oxartextends');
    }

    public function getFields(): array
    {
        return [
            'Master'        => "{$this->articleView}.oxid",
            'Name'          => "{$this->articleView}.oxtitle",
            'ImageUrl'      => "{$this->articleView}.oxpic1",
            'Short'         => "{$this->articleView}.oxshortdesc",
            'ProductNumber' => "{$this->articleView}.oxartnum",
            'Description'   => "{$this->articleExtendView}.oxlongdesc",
            'ArticleUrl'    => "{$this->seoTable}.oxseourl",
            'Brand'         => "{$this->manufacturerView}.oxtitle",
            'Availability'  => "IF({$this->articleView}.oxvarcount > 0, 1, 0)",
            'OxidId'        => "{$this->articleView}.oxid",
            'CategoryPath'  => "GROUP_CONCAT(DISTINCT {$this->object2CategoryView}.oxcatnid SEPARATOR ',')",
        ];
    }
}
