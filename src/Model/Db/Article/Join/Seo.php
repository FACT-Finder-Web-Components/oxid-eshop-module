<?php

declare(strict_types=1);

namespace Omikron\FactFinder\Oxid\Model\Db\Article\Join;

use Omikron\FactFinder\Oxid\Contract\Db\JoinInterface;

class Seo extends AbstractJoin implements JoinInterface
{
    /** @var string */
    protected $shopId;

    /** @var string */
    protected $seoTable = 'oxseo';

    public function __construct()
    {
        parent::__construct();
        $this->shopId = $this->config->getShopId();
    }

    public function getJoin(): array
    {
        return [
            $this->articleView => [
                'joinType' => 'left',
                'joinTable' => "{$this->seoTable}",
                'joinAlias' => "{$this->seoTable}",
                'joinCondition' => "{$this->seoTable}.oxtype = 'oxarticle'
                           AND {$this->seoTable}.oxobjectid = {$this->articleView}.oxid
                           AND {$this->seoTable}.oxshopid='" . $this->shopId . "'
                           AND {$this->seoTable}.oxparams = (
                               SELECT o2c.oxcatnid
                               FROM {$this->object2CategoryView} o2c
                               WHERE o2c.oxobjectid = {$this->articleView}.oxid
                               ORDER BY o2c.oxtime
                               LIMIT 1
                           )
                           AND {$this->seoTable}.oxlang = {$this->langId}"
            ],
        ];
    }
}
