<?php

declare(strict_types=1);

namespace Omikron\FactFinder\Oxid\Export\Field\Category;

use OxidEsales\Eshop\Application\Model\Category;
use OxidEsales\Eshop\Core\Registry;

class Deeplink implements FieldInterface
{
    /** @var string */
    private $url;

    public function __construct()
    {
        $this->url = sprintf(
            '%s%s',
            Registry::getConfig()->getCurrentShopUrl(),
            Registry::getLang()->getLanguageAbbr()
        );
    }

    public function getName(): string
    {
        return 'Deeplink';
    }

    public function getValue(Category $category, Category $parent): string
    {
        return str_replace($this->url, '', $category->getLink());
    }
}
