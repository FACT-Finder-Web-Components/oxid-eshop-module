<?php

declare(strict_types=1);

namespace Tests\Variant\Export\Field;

use Omikron\FactFinder\Oxid\Export\Field\Article\FieldInterface;
use OxidEsales\Eshop\Application\Model\Article;

class DisplayError implements FieldInterface
{
    public function getName(): string
    {
        return 'DisplayError';
    }

    public function getValue(Article $article, Article $parent): string
    {
        $value = $article->getFieldData('oxarticles__oxdisplayerror');

        return is_string($value) ? $value : '';
    }
}
