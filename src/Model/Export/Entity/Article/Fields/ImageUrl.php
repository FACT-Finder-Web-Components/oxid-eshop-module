<?php

declare(strict_types=1);

namespace Omikron\FactFinder\Oxid\Model\Export\Entity\Article\Fields;

use Omikron\FactFinder\Oxid\Contract\Export\Entity\FieldModifierInterface;
use Omikron\FactFinder\Oxid\Model\Export\AbstractEntity;
use OxidEsales\Eshop\Core\Config;
use OxidEsales\Eshop\Core\Registry;

class ImageUrl implements FieldModifierInterface
{
    /** @var  Config */
    private $config;

    public function __construct()
    {
        $this->config = Registry::getConfig();
    }

    public function getName(): string
    {
        return 'ImageUrl';
    }

    public function getValue(AbstractEntity $entity): string
    {
        $imgSize = $this->config->getConfigParam('sZoomImageSize');
        return (string) Registry::getPictureHandler()->getProductPicUrl('product/1/', $entity->getImageUrl(), $imgSize);
    }
}
