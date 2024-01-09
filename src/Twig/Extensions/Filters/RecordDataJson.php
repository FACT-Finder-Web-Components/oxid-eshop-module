<?php

declare(strict_types=1);

namespace Omikron\FactFinder\Oxid\Twig\Extensions\Filters;

use Omikron\FactFinder\Communication\Client\ClientBuilder;
use Omikron\FactFinder\Communication\Credentials;
use Omikron\FactFinder\Communication\Resource\AdapterFactory;
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

        $username = (string) $moduleSettingService->getString('ffPublicUsername', 'ffwebcomponents');
        $password = (string) $moduleSettingService->getString('ffPublicPassword', 'ffwebcomponents');

        $recordId    = (string) $article->getFieldData('oxartnum');
        $credentials = new Credentials(
            $username,
            $password,
            (string) $moduleSettingService->getString('ffAuthPrefix', 'ffwebcomponents'),
            (string) $moduleSettingService->getString('ffAuthPostfix', 'ffwebcomponents')
        );
        $clientBuilder = oxNew(ClientBuilder::class)
            ->withServerUrl((string) $moduleSettingService->getString('ffServerUrl', 'ffwebcomponents'))
            ->withCredentials($credentials)
            ->withVersion((string) $moduleSettingService->getString('ffApiVersion', 'ffwebcomponents'));
        $adapterFactory = new AdapterFactory(
            $clientBuilder,
            (string) $moduleSettingService->getString('ffVersion', 'ffwebcomponents'),
            (string) $moduleSettingService->getString('ffApiVersion', 'ffwebcomponents')
        );
        $searchAdapter = $adapterFactory->getSearchAdapter();
        $response      = $searchAdapter->records(
            $moduleSettingService->getCollection('ffChannel', 'ffwebcomponents')[Registry::getLang()->getLanguageAbbr()],
            '',
            [
                'idType'        => 'productNumber',
                'productNumber' => $recordId,
                'format'        => 'json'
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
