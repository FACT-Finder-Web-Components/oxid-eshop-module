<?php

declare(strict_types=1);

namespace Tests\Variant\Export\Field;

use Omikron\FactFinder\Oxid\Export\Field\FieldInterface;
use OxidEsales\Eshop\Application\Model\Article;

class DisplayError implements FieldInterface
{
    public function getName(): string
    {
        return 'DisplayError';
    }

    public function getValue(Article $article, Article $parent): string
    {
        return $article->getFieldData('oxarticles__oxdisplayerror');
    }
}
