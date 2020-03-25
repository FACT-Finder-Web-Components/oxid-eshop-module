<?php

declare(strict_types=1);

namespace Omikron\FactFinder\Oxid\Model\Api;

use Omikron\FactFinder\Oxid\Model\Api\Resource\Builder as ResourceBuilder;
use Omikron\FactFinder\Oxid\Model\Config\Authorization;
use OxidEsales\Eshop\Core\Config;
use OxidEsales\Eshop\Core\Registry;

class PushImport
{
    /** @var ClientFactory */
    protected $clientFactory;

    /** @var Config */
    protected $config;

    public function __construct(ClientFactory $clientFactory)
    {
        $this->clientFactory = $clientFactory;
        $this->config        = Registry::getConfig();
    }

    public function execute(array $params = [])
    {
        if (!$this->isPushImportEnabled()) {
            return false;
        }

        $resource = oxNew(ResourceBuilder::class)
            ->withServerUrl($this->config->getConfigParam('ffServerUrl'))
            ->withApiVersion($this->config->getConfigParam('ffApiVersion'))
            ->withClient($this->clientFactory->create())
            ->withCredentials(oxNew(Credentials::class, ...oxNew(Authorization::class)->getParameters()))
            ->build();

        $response = [];
        $channel  = $this->config->getConfigParam('ffChannel');
        foreach ($this->getPushImportTypes() as $type) {
            $response = array_merge_recursive($response, $resource->import($type, $channel, $params));
        }

        return $response && !($response['errors'] ?? $response['error'] ?? false);
    }

    protected function isPushImportEnabled(): bool
    {
        return (bool) $this->config->getConfigParam('ffAutomaticImport');
    }

    protected function getPushImportTypes(): array
    {
        return array_filter(['data', 'suggest', 'recommendation'], function (string $type): bool {
            return (bool) $this->config->getConfigParam('ffAutomaticImport' . ucfirst($type));
        });
    }
}
