<?php

declare(strict_types=1);

namespace Omikron\FactFinder\Oxid\Component\Widget;

use Omikron\FactFinder\Oxid\Model\Config\Communication as CommunicationConfig;
use OxidEsales\Eshop\Application\Component\Widget\WidgetController;
use OxidEsales\Eshop\Core\Registry;

class WebComponent extends WidgetController
{
    public function render()
    {
        parent::render();
        $sForceTemplate = $this->getViewParameter('oxwtemplate');

        if ($sForceTemplate) {
            $this->_sTemplate = $sForceTemplate;
        }

        return $this->_sTemplate;
    }

    public function getCommunicationParams(): array
    {
        $config = oxNew(CommunicationConfig::class, Registry::getConfig()->getTopActiveView());
        return array_filter($config->getParameters());
    }
}
