<?php

declare(strict_types=1);

namespace Omikron\FactFinder\Oxid\Export\Field;

use Omikron\FactFinder\Oxid\Export\Field\Article\FieldInterface;
use OxidEsales\Eshop\Application\Model\Article;

class Keywords implements FieldInterface
{
    public function getName(): string
    {
        return 'Keywords';
    }

    public function getValue(Article $article, Article $parent): string
    {
        return $article->getFieldData('oxsearchkeys') ?: $parent->getFieldData('oxsearchkeys');
    }
}
