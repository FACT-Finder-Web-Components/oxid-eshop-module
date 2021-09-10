<?php

declare(strict_types=1);

namespace Omikron\FactFinder\Oxid\Export\Field;

use OxidEsales\Eshop\Application\Model\Article;

interface FieldInterface
{
    public function getName(): string;

    public function getValue(Article $article, Article $parent): string;
}
