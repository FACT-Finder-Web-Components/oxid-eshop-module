<?php

declare(strict_types=1);

namespace Omikron\FactFinder\Oxid\Component\Widget;

use Omikron\FactFinder\Oxid\Model\Config\Communication as CommunicationConfig;
use OxidEsales\Eshop\Application\Component\Widget\WidgetController;

class WebComponent extends WidgetController
{
    public function getCommunicationParams(): array
    {
        return array_filter(oxNew(CommunicationConfig::class, $this->getConfig()->getTopActiveView())->getParameters());
    }
}
