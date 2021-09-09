<?php

declare(strict_types=1);

namespace Omikron\FactFinder\Oxid\Model\Config;

use Omikron\FactFinder\Oxid\Contract\Config\ParametersSourceInterface;
use OxidEsales\Eshop\Core\Registry;

class Authorization implements ParametersSourceInterface
{
    private $config;

    public function __construct()
    {
        $this->config = Registry::getConfig();
    }

    /**
     * @return array
     */
    public function getParameters(): array
    {
        return [
             $this->config->getConfigParam('ffUsername'),
             $this->config->getConfigParam('ffPassword'),
             $this->config->getConfigParam('ffAuthPrefix'),
             $this->config->getConfigParam('ffAuthPostfix'),
        ];
    }
}
