<?php

declare(strict_types=1);

namespace Omikron\FactFinder\Oxid\Utilities\Ssr;

use Omikron\FactFinder\Communication\Client\ClientBuilder;
use Omikron\FactFinder\Communication\Version;
use OxidEsales\EshopCommunity\Internal\Framework\Module\Facade\ModuleSettingServiceInterface;

class SearchAdapter
{
    public function __construct(
        private ClientBuilder $clientBuilder,
        private ModuleSettingServiceInterface $moduleSettingService,
    ) {
    }

    public function search(string $paramString, bool $navigationRequest): string
    {
        $client = $this->clientBuilder
            ->withServerUrl((string) $this->moduleSettingService->getString('ffServerUrl', 'ffwebcomponents'))
            ->withApiKey((string) $this->moduleSettingService->getString('ffApiKey', 'ffwebcomponents'))
            ->withVersion(Version::NG)
            ->build();

        $endpoint = $this->createEndpoint($paramString, $navigationRequest);
        $response = $client->request('GET', $endpoint);

        return $response->getBody()->getContents();
    }

    private function createEndpoint(string $paramString, bool $navigationRequest): string
    {
        $channel    = (string) $this->moduleSettingService->getCollection('ffChannel', 'ffwebcomponents')['en'];
        $endpoint   = $navigationRequest ? 'navigation' : 'search';

        return "rest/v5/{$endpoint}/{$channel}?{$paramString}";
    }
}
