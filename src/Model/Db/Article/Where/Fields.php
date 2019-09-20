<?php

declare(strict_types=1);

namespace Omikron\FactFinder\Oxid\Model\Db\Article\Where;

use Omikron\FactFinder\Oxid\Contract\Db\WhereInterface;
use OxidEsales\Eshop\Application\Model\Article;

class Fields implements WhereInterface
{
    protected $articleView;

    public function __construct()
    {
        $this->articleView = getViewName('oxarticles');
    }

    public function getClause(): array
    {
        return [
            'active' => oxNew(Article::class)->getSqlActiveSnippet(false),
            'catactive' => oxNew(Article::class)->getSqlActiveSnippet(false),
            'variants' => "{$this->articleView}.oxparentid = ''"
        ];
    }
}
