<?php
namespace Omikron\FactFinder\Oxid\Export\Field\Category;


use Omikron\FactFinder\Oxid\Export\Field\BaseFieldInterface;
use OxidEsales\Eshop\Application\Model\Article;

interface FieldInterface extends BaseFieldInterface
{
    public function getName(): string;

    public function getValue(Article $article, Article $parent): string;
}
