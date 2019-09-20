<?php

declare(strict_types=1);

namespace Omikron\FactFinder\Oxid\Model\Db\Article\Join;

use OxidEsales\Eshop\Core\Config;
use OxidEsales\Eshop\Core\Language;
use OxidEsales\Eshop\Core\Registry;

class AbstractJoin
{
    /** @var Config */
    protected $config;

    /** @var Language */
    protected $language;

    /** @var int */
    protected $langId;

    /** @var string */
    protected $articleView;

    /** @var string */
    protected $articleExtendView;

    /** @var string */
    protected $object2CategoryView;

    /** @var string */
    protected $attributeView;

    /** @var string */
    protected $object2AttributeView;

    public function __construct()
    {
        $this->config = Registry::getConfig();
        $this->language = Registry::getLang();
        $this->articleView = getViewName('oxarticles');
        $this->langId = $this->language->getBaseLanguage();
        $this->object2CategoryView = getViewName('oxobject2category');
        $this->articleExtendView = getViewName('oxartextends');
        $this->object2AttributeView = getViewName('oxobject2attribute');
        $this->attributeView = getViewName('oxattribute');
    }
}
