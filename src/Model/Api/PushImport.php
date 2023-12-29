<?php

declare(strict_types=1);

namespace Omikron\FactFinder\Oxid\Model\Api;

use Omikron\FactFinder\Communication\Client\ClientBuilder;
use Omikron\FactFinder\Communication\Credentials;
use Omikron\FactFinder\Communication\Resource\AdapterFactory;
use Omikron\FactFinder\Oxid\Model\Config\Authorization;
use OxidEsales\Eshop\Core\Registry;
use OxidEsales\EshopCommunity\Internal\Container\ContainerFactory;
use OxidEsales\EshopCommunity\Internal\Framework\Module\Facade\ModuleSettingServiceInterface;

class PushImport
{
    private ModuleSettingServiceInterface $moduleSettingService;

    public function __construct()
    {
        $this->moduleSettingService = ContainerFactory::getInstance()
            ->getContainer()
            ->get(ModuleSettingServiceInterface::class);
    }

    public function execute(array $params = [])
    {
        if (!$this->isPushImportEnabled()) {
            return false;
        }

        $version    = (string) $this->moduleSettingService->getString('ffVersion', 'ffwebcomponents');
        $apiVersion = (string) $this->moduleSettingService->getString('ffApiVersion', 'ffwebcomponents') ?? 'v4';

        $clientBuilder = oxNew(ClientBuilder::class)
            ->withServerUrl((string) $this->moduleSettingService->getString('ffServerUrl', 'ffwebcomponents'))
            ->withCredentials(oxNew(Credentials::class, ...oxNew(Authorization::class)->getParameters()));

        $importAdapter = (new AdapterFactory($clientBuilder, $version, $apiVersion))->getImportAdapter();
        $channel       = $this->getChannel(Registry::getLang()->getLanguageAbbr());

        foreach ($this->getPushImportTypes($version) as $type) {
            $importAdapter->import($channel, $type, $params);
        }

        return true;
    }

    protected function isPushImportEnabled(): bool
    {
        return (bool) $this->moduleSettingService->getBoolean('ffAutomaticImport', 'ffwebcomponents');
    }

    protected function getPushImportTypes(string $version): array
    {
        return array_map(function (string $type) use ($version): string {
            return $version === 'ng' && $type === 'data' ? 'search' : $type;
        }, array_filter(['data', 'suggest', 'recommendation'], function (string $type): bool {
            return (bool) $this->moduleSettingService->getBoolean(sprintf('ffAutomaticImport%s', ucfirst($type)), 'ffwebcomponents');
        }));
    }

    protected function getChannel(string $lang): string
    {
        return $this->moduleSettingService->getCollection('ffChannel', 'ffwebcomponents')[$lang];
    }
}
