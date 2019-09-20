<?php

declare(strict_types=1);

namespace Omikron\FactFinder\Oxid\Model\Export\Entity\Article\Fields;

use Omikron\FactFinder\Oxid\Contract\Export\Entity\FieldModifierInterface;
use Omikron\FactFinder\Oxid\Model\Export\AbstractEntity;
use OxidEsales\Eshop\Application\Model\Article as OxidArticle;
use OxidEsales\Eshop\Core\Config;
use OxidEsales\Eshop\Core\Registry;

class ArticleUrl implements FieldModifierInterface
{
    /** @var Config */
    private $config;

    public function __construct()
    {
        $this->config = Registry::getConfig();
    }

    public function getName(): string
    {
        return 'ArticleUrl';
    }

    public function getValue(AbstractEntity $entity): string
    {
        $articleUrl = $entity->getArticleUrl();
        if ($articleUrl != '') {
            return $this->config->getShopUrl() . $articleUrl;
        } else {
            $article = oxNew(OxidArticle::class);
            $article->load($entity->getMaster());
            return $article->getLink();
        }
    }
}
