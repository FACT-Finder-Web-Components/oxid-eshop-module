<?php

declare(strict_types=1);

namespace Omikron\FactFinder\Oxid\Model\Db\Article\Select;

use Omikron\FactFinder\Oxid\Contract\Db\SelectInterface;

class Price implements SelectInterface
{
    /** @var string */
    private $articleView;

    public function __construct()
    {
        $this->articleView = getViewName('oxarticles');
    }

    public function getFields(): array
    {
        return [
            'Price' => "if (
                {$this->articleView}.oxprice = '0' and {$this->articleView}.oxvarcount > 0,
                {$this->articleView}.oxvarminprice,
                {$this->articleView}.oxprice
            ) as Price"
        ];
    }
}
