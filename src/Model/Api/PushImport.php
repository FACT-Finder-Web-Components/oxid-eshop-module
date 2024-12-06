<?php

declare(strict_types=1);

namespace Omikron\FactFinder\Oxid\Model\Api;

use Omikron\FactFinder\Communication\Client\ClientBuilder;
use Omikron\FactFinder\Communication\Resource\AdapterFactory;
use Omikron\FactFinder\Communication\Version;
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

        $version    = Version::NG;
        $apiVersion = 'v5';

        $clientBuilder = oxNew(ClientBuilder::class)
            ->withServerUrl((string) $this->moduleSettingService->getString('ffServerUrl', 'ffwebcomponents'))
            ->withApiKey((string) $this->moduleSettingService->getString('ffApiKey', 'ffwebcomponents'));

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
        return array_map(fn (string $type): string => $version === 'ng' && $type === 'data' ? 'search' : $type, array_filter(['data', 'suggest', 'recommendation'], fn (string $type): bool => (bool) $this->moduleSettingService->getBoolean(sprintf('ffAutomaticImport%s', ucfirst($type)), 'ffwebcomponents')));
    }

    protected function getChannel(string $lang): string
    {
        return $this->moduleSettingService->getCollection('ffChannel', 'ffwebcomponents')[$lang];
    }
}
