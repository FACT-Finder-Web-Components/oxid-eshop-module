<?php

declare(strict_types=1);

namespace Omikron\FactFinder\Oxid\Model\Config;

use Omikron\FactFinder\Oxid\Contract\Config\ParametersSourceInterface;
use OxidEsales\Eshop\Core\Config;
use OxidEsales\Eshop\Core\Registry;

class Tracking implements ParametersSourceInterface
{
    /** @var Config */
    private $config;

    public function __construct()
    {
        $this->config = Registry::getConfig();
    }

    public function getParameters(): array
    {
        return [
            'addToCart' => [
                'count' => $this->getConfig('ffTrackingAddToCartCount') ?? 'count_as_one',
            ],
        ];
    }

    protected function getConfig(string $name)
    {
        return $this->config->getConfigParam($name);
    }
}
