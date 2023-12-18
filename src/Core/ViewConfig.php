<?php

declare(strict_types=1);

namespace Omikron\FactFinder\Oxid\Core;

use Omikron\FactFinder\Oxid\Component\Widget\WebComponent;
use OxidEsales\Eshop\Core\Registry;
use OxidEsales\EshopCommunity\Internal\Container\ContainerFactory;
use OxidEsales\EshopCommunity\Internal\Framework\Module\Facade\ModuleSettingServiceInterface;

/**
 * @mixin \OxidEsales\Eshop\Core\ViewConfig
 */
class ViewConfig extends ViewConfig_parent
{
    private readonly WebComponent $webcomponent;
    private readonly ModuleSettingServiceInterface $moduleSettingService;

    public function __construct()
    {
        $this->webcomponent = oxNew(WebComponent::class);
        $this->moduleSettingService = ContainerFactory::getInstance()
            ->getContainer()
            ->get(ModuleSettingServiceInterface::class);
    }
    public function getFFStringConfigParam(string $paramName): string
    {
        return (string) $this->moduleSettingService->getString($paramName, 'ffwebcomponents');
    }

    public function getFFArrayConfigParam(string $paramName): array
    {
        return $this->moduleSettingService->getCollection($paramName, 'ffwebcomponents');
    }

    public function getFFBoolConfigParam(string $paramName): bool
    {
        return $this->moduleSettingService->getBoolean($paramName, 'ffwebcomponents');
    }

    public function getCommunicationParams(): array
    {
        return $this->webcomponent->getCommunicationParams();
    }

    public function getTrackingSettings(): array
    {
        return $this->webcomponent->getTrackingSettings();
    }

    public function useSidAsUserId(): bool
    {
        return $this->webcomponent->useSidAsUserId();
    }

    public function getSearchImmediate(): bool
    {
        return $this->webcomponent->getSearchImmediate();
    }

//
//    /**
//     * Note added because of missing method error in article templates
//     */
//    public function getConfig()
//    {
//        return Registry::getConfig();
//    }
}
