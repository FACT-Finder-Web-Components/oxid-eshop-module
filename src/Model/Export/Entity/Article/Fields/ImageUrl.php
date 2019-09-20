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

    /** @var string */
    private $imageBaseUrl = '';

    /** @var string */
    private $placeholder = '';

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
        if ($this->imageBaseUrl == '') {
            $imgSize = $this->config->getConfigParam('sZoomImageSize');
            $imageHandler = Registry::getPictureHandler();
            if ($this->placeholder == '') {
                $this->placeholder = $imageHandler->getProductPicUrl('product/1/', '', $imgSize, 1);
            }
            $this->imageBaseUrl = str_replace('nopic.jpg', '', $this->placeholder);
        }
        return $entity->getImageUrl() != '' ? ($this->imageBaseUrl . $entity->getImageUrl()) : $this->placeholder;
    }
}
