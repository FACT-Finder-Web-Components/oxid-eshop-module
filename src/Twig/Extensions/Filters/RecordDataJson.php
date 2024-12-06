<?php

declare(strict_types=1);

namespace Omikron\FactFinder\Oxid\Twig\Extensions\Filters;

use Omikron\FactFinder\Communication\Client\ClientBuilder;
use Omikron\FactFinder\Communication\Resource\AdapterFactory;
use Omikron\FactFinder\Communication\Version;
use OxidEsales\Eshop\Core\Model\BaseModel;
use OxidEsales\Eshop\Core\Registry;
use OxidEsales\EshopCommunity\Internal\Container\ContainerFactory;
use OxidEsales\EshopCommunity\Internal\Framework\Module\Facade\ModuleSettingServiceInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class RecordDataJson extends AbstractExtension
{
    public function getFilters(): array
    {
        return [
            new TwigFilter('record_data_json', [$this, 'recordDataJson']),
        ];
    }

    public function recordDataJson(BaseModel $article): false|string
    {
        /** @var ModuleSettingServiceInterface $moduleSettingService */
        $moduleSettingService = ContainerFactory::getInstance()
            ->getContainer()
            ->get(ModuleSettingServiceInterface::class);

        $recordId    = (string) $article->getFieldData('oxartnum');

        $clientBuilder = oxNew(ClientBuilder::class)
            ->withServerUrl((string) $moduleSettingService->getString('ffServerUrl', 'ffwebcomponents'))
            ->withApiKey((string) $moduleSettingService->getString('ffApiKey', 'ffwebcomponents'))
            ->withVersion(Version::NG);
        $adapterFactory = new AdapterFactory(
            $clientBuilder,
            Version::NG,
            'v5'
        );
        $searchAdapter = $adapterFactory->getSearchAdapter();
        $response      = $searchAdapter->records(
            $moduleSettingService->getCollection('ffChannel', 'ffwebcomponents')[Registry::getLang()->getLanguageAbbr()],
            '',
            [
                'idType'        => 'productNumber',
                'productNumber' => $recordId,
                'format'        => 'json',
            ]
        );

        $record = $response['records'][0] ?? [];
        unset($record['Description']);

        if ($record === []) {
            return json_encode([]);
        }

        $jsonData = json_encode([
            'id'     => $recordId,
            'record' => $record,
        ]);

        return str_replace(['"\"', '\""'], '"', $jsonData);
    }
}
