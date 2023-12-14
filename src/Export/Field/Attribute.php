<?php

declare(strict_types=1);

namespace Omikron\FactFinder\Oxid\Export\Field;

use Omikron\FactFinder\Oxid\Export\Field\Article\FieldInterface;
use OxidEsales\Eshop\Application\Model\Article;
use OxidEsales\Eshop\Core\DatabaseProvider;
use OxidEsales\Eshop\Core\TableViewNameGenerator;

class Attribute implements FieldInterface
{
    public function __construct(private readonly string $name)
    {
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getValue(Article $article, Article $parent): string // phpcs:ignore
    {
        return (string) ($this->getData($article)[$this->name] ?? '');
    }

    protected function getData(Article $article): array
    {
        $attributes = $this->getAttributes($article->getId(), $article->getParentId());

        return array_reduce($attributes, function (array $result, array $attribute) {
            return $result + [$attribute['OXTITLE'] => $attribute['OXVALUE']];
        }, []);
    }

    protected function getAttributes($articleId, $parentId): array
    {
        $connection         = DatabaseProvider::getDb(DatabaseProvider::FETCH_MODE_ASSOC);
        $tableViewNameGenerator = new TableViewNameGenerator();
        $oxAttribute        = $tableViewNameGenerator->getViewName('oxattribute');
        $oxobject2Attribute = $tableViewNameGenerator->getViewName('oxobject2attribute');

        $select = "select {$oxAttribute}.`oxid`, {$oxAttribute}.`oxtitle`, o2a.`oxvalue` from {$oxobject2Attribute} as o2a "
            . "left join {$oxAttribute} on {$oxAttribute}.oxid = o2a.oxattrid "
            . "where o2a.oxobjectid = :oxobjectid and o2a.oxvalue != '' "
            . "order by o2a.oxpos, {$oxAttribute}.oxpos";

        $articleAttributes = $connection->getAll($select, [':oxobjectid' => $articleId]);

        if ($parentId) {
            $parentAttributes = $connection->getAll($select, [':oxobjectid' => $parentId]);
            return $this->mergeAttributes($articleAttributes, $parentAttributes);
        }

        return $articleAttributes;
    }

    protected function mergeAttributes($articleAttributes, $parentAttributes): array
    {
        if (!count($parentAttributes)) {
            return $articleAttributes;
        }

        return array_values(array_reduce($articleAttributes + $parentAttributes, function (array $added, array $attribute): array {
            return in_array($attribute['OXID'], $added) ? $added : $added + [$attribute['OXID'] => $attribute];
        }, []));
    }
}
