<?php

declare(strict_types=1);

namespace Omikron\FactFinder\Oxid\Export\Field;

use Omikron\FactFinder\Oxid\Export\Field\Article\FieldInterface;
use OxidEsales\Eshop\Application\Model\Article;
use OxidEsales\Eshop\Application\Model\Manufacturer;

class Brand implements FieldInterface
{
    /** @var Manufacturer[] */
    private $brands;

    public function getName(): string
    {
        return 'Brand';
    }

    public function getValue(Article $article, Article $parent): string // phpcs:ignore
    {
        $id = $parent->getManufacturerId();
        if ($id) {
            $brand = $this->brands[$id] = $this->brands[$id] ?? $parent->getManufacturer();
            return $brand ? (string) $brand->getTitle() : '';
        }
        return '';
    }
}
