<?php

declare(strict_types=1);

namespace Omikron\FactFinder\Oxid\Model\Db\Article\Variant\Join;

use Omikron\FactFinder\Oxid\Contract\Db\JoinInterface;
use Omikron\FactFinder\Oxid\Model\Db\Article\Join\Seo as ParentSeo;

class Seo extends ParentSeo implements JoinInterface
{
    public function getJoin(): array
    {
        return [
            $this->articleView  => [
                'joinType' => 'left',
                'joinTable' => "{$this->seoTable}",
                'joinAlias' => "{$this->seoTable }",
                'joinCondition' => "{$this->seoTable}.oxtype = 'oxarticle'
                           AND oxseo.oxobjectid = {$this->articleView}.oxparentid
                           AND oxseo.oxshopid='" . $this->shopId . "'
                           AND oxseo.oxparams = (
                               SELECT o2c.oxcatnid
                               FROM {$this->object2CategoryView} o2c
                               WHERE o2c.oxobjectid = {$this->articleView}.oxparentid
                               ORDER BY o2c.oxtime
                               LIMIT 1
                           )
                           AND oxseo.oxlang = {$this->langId}"
            ],
        ];
    }
}
