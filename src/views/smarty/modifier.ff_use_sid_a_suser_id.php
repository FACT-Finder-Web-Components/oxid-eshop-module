<?php

declare(strict_types=1);

use OxidEsales\Eshop\Core\Config;
use OxidEsales\Eshop\Core\Model\BaseModel;
use OxidEsales\Eshop\Core\Registry;

function smarty_modifier_ff_use_sid_a_suser_id(BaseModel $article): bool
{
    /** @var Config $config */
    $config = Registry::getConfig();

    return $config->getConfigParam('ffSidAsUserId') ?? false;
}
