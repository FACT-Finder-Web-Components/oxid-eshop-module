<?php

declare(strict_types=1);

namespace Omikron\FactFinder\Oxid\Model\Config;

use Omikron\FactFinder\Oxid\Contract\Config\ParametersSourceInterface;
use OxidEsales\EshopCommunity\Internal\Container\ContainerFactory;
use OxidEsales\EshopCommunity\Internal\Framework\Module\Facade\ModuleSettingServiceInterface;

class Authorization implements ParametersSourceInterface
{
    private ModuleSettingServiceInterface $moduleSettingService;

    public function __construct()
    {
        $this->moduleSettingService = ContainerFactory::getInstance()
            ->getContainer()
            ->get(ModuleSettingServiceInterface::class);
    }

    /**
     * @return array
     */
    public function getParameters(): array
    {
        return [
            (string) $this->moduleSettingService->getString('ffUsername', 'ffwebcomponents'),
            (string) $this->moduleSettingService->getString('ffPassword', 'ffwebcomponents'),
            (string) $this->moduleSettingService->getString('ffAuthPrefix', 'ffwebcomponents'),
            (string) $this->moduleSettingService->getString('ffAuthPostfix', 'ffwebcomponents'),
        ];
    }
}
