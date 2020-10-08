<?php

declare(strict_types=1);

namespace Omikron\FactFinder\Oxid\Model\Api;

use Omikron\FactFinder\Oxid\Model\Api\Resource\Builder as ResourceBuilder;
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

        $apiVersion = $this->config->getConfigParam('ffApiVersion');

        $resource = oxNew(ResourceBuilder::class)
            ->withServerUrl($this->config->getConfigParam('ffServerUrl'))
            ->withApiVersion($apiVersion)
            ->withCredentials(oxNew(Credentials::class, ...oxNew(Authorization::class)->getParameters()))
            ->build();

        $response = [];
        $channel  = $this->getChannel(Registry::getLang()->getLanguageAbbr());
        foreach ($this->getPushImportTypes($apiVersion) as $type) {
            $response = array_merge_recursive($response, $resource->import($type, $channel, $params));
        }

        return $response && !($response['errors'] ?? $response['error'] ?? false);
    }

    protected function isPushImportEnabled(): bool
    {
        return (bool) $this->config->getConfigParam('ffAutomaticImport');
    }

    protected function getPushImportTypes(string $apiVersion): array
    {
        return array_map(function (string $type) use ($apiVersion): string {
            return $apiVersion === 'ng' && $type === 'data' ? 'search' : $type;
        }, array_filter(['data', 'suggest', 'recommendation'], function (string $type): bool {
            return (bool) $this->config->getConfigParam('ffAutomaticImport' . ucfirst($type));
        }));
    }

    protected function getChannel(string $lang): string
    {
        return $this->config->getConfigParam('ffChannel')[$lang];
    }
}
