<?php

declare(strict_types=1);

namespace Omikron\FactFinder\Oxid\Model\Db\Article\Variant\Select;

use Omikron\FactFinder\Oxid\Contract\Db\SelectInterface;
use Omikron\FactFinder\Oxid\Model\Db\Article\Select\Base as ParentBase;
use Omikron\FactFinder\Oxid\Model\Db\Article\Variant\Join\ParentArticle;

class Base extends ParentBase implements SelectInterface
{
    public function getFields(): array
    {
        $parentArticleTable = ParentArticle::PARENT_ARTICLE_ALIAS;

        return [
                'Master' => "{$this->articleView}.oxparentid as Master",
                'Name' => "$parentArticleTable.oxtitle as Name",
                'ImageUrl' => "if ({$this->articleView}.oxpic1 = '', $parentArticleTable.oxpic1, {$this->articleView}.oxpic1) as ImageUrl",
                'Short' => "$parentArticleTable.oxshortdesc as Short"
            ] + parent::getFields();
    }
}
