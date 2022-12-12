<?php

declare(strict_types=1);

namespace Omikron\FactFinder\Oxid\Model\Api;

use Omikron\FactFinder\Communication\Client\ClientBuilder;
use Omikron\FactFinder\Communication\Credentials;
use Omikron\FactFinder\Communication\Resource\AdapterFactory;
use Omikron\FactFinder\Oxid\Model\Config\Authorization;
use OxidEsales\Eshop\Core\Config;
use OxidEsales\Eshop\Core\Registry;

class PushImport
{
    /** @var Config */
    protected $config;

    public function __construct()
    {
        $this->config = Registry::getConfig();
    }

    public function execute(array $params = [])
    {
        if (!$this->isPushImportEnabled()) {
            return false;
        }

        $version = $this->config->getConfigParam('ffVersion');
        $apiVersion = (string) $this->config->getConfigParam('ffApiVersion') ?? 'v4';

        $clientBuilder = oxNew(ClientBuilder::class)
            ->withServerUrl($this->config->getConfigParam('ffServerUrl'))
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
        return (bool) $this->config->getConfigParam('ffAutomaticImport');
    }

    protected function getPushImportTypes(string $version): array
    {
        return array_map(function (string $type) use ($version): string {
            return $version === 'ng' && $type === 'data' ? 'search' : $type;
        }, array_filter(['data', 'suggest', 'recommendation'], function (string $type): bool {
            return (bool) $this->config->getConfigParam('ffAutomaticImport' . ucfirst($type));
        }));
    }

    protected function getChannel(string $lang): string
    {
        return $this->config->getConfigParam('ffChannel')[$lang];
    }
}
