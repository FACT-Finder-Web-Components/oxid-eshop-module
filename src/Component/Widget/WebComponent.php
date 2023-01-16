<?php

declare(strict_types=1);

namespace Omikron\FactFinder\Oxid\Component\Widget;

use Omikron\FactFinder\Oxid\Model\Config\Communication as CommunicationConfig;
use Omikron\FactFinder\Oxid\Model\Config\Tracking as TrackingConfig;
use OxidEsales\Eshop\Application\Component\Widget\WidgetController;
use OxidEsales\Eshop\Core\Registry;

class WebComponent extends WidgetController
{
    /** @var CommunicationConfig */
    private $config;

    /** @var TrackingConfig */
    private $trackingConfig;

    public function __construct()
    {
        $this->config         = oxNew(CommunicationConfig::class, Registry::getConfig()->getTopActiveView());
        $this->trackingConfig = oxNew(TrackingConfig::class, Registry::getConfig()->getTopActiveView());
    }

    public function getCommunicationParams(): array
    {
        $params = array_filter($this->config->getParameters(), function (string $name) {
            return !in_array($name, ['user-id', 'search-immediate']);
        }, ARRAY_FILTER_USE_KEY);

        return $params;
    }

    public function getSearchImmediate(): bool
    {
        return $this->config->getParameters()['search-immediate'] === 'true';
    }

    public function getTrackingSettings(): array
    {
        return $this->config->getParameters();
    }
}
