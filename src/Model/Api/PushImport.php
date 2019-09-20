<?php

declare(strict_types=1);

namespace Omikron\FactFinder\Oxid\Model\Api;

use Omikron\FactFinder\Oxid\Contract\Api\ClientInterface;
use OxidEsales\Eshop\Core\Config;
use OxidEsales\Eshop\Core\Registry;

class PushImport
{
    /** @var ClientInterface */
    private $apiClient;

    /** @var Config */
    private $config;

    /** @var string */
    private $apiName = 'Import.ff';

    public function __construct(ClientFactory $clientFactory)
    {
        $this->apiClient = $clientFactory->create();
        $this->config    = Registry::getConfig();
    }

    public function execute(array $params = [])
    {
        if (!$this->isPushImportEnabled()) {
            return false;
        }
        $params += [
            'channel' => $this->config->getConfigParam('ffChannel'),
            'quiet' => 'true',
            'download' => 'true',
        ];

        $response = [];
        $endpoint = $this->config->getConfigParam('ffServerUrl') . '/' . $this->apiName;
        foreach (['suggest','data'] as $type) {
            $params['type'] = $type;
            $response = array_merge_recursive($response, $this->apiClient->sendRequest($endpoint, $params));
        }

        return $response && !(isset($response['errors']) || isset($response['error']));
    }

    private function isPushImportEnabled(): bool
    {
        return $this->config->getConfigParam('ffAutomaticImport');
    }
}
