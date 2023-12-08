<?php

declare(strict_types=1);

use Omikron\FactFinder\Communication\Client\ClientBuilder;
use Omikron\FactFinder\Communication\Credentials;
use Omikron\FactFinder\Communication\Resource\AdapterFactory;
use OxidEsales\Eshop\Core\Config;
use OxidEsales\Eshop\Core\Model\BaseModel;
use OxidEsales\Eshop\Core\Registry;

function smarty_modifier_record_data_json(BaseModel $article): string
{
    /** @var Config $config */
    $config = Registry::getConfig();
    $username = $config->getConfigParam('ffPublicUsername');
    $password = $config->getConfigParam('ffPublicPassword');

    if ($username === '' || $password === '') {
        $username = $config->getConfigParam('ffUsername');
        $password = $config->getConfigParam('ffPassword');
    }

    $recordId = (string) $article->getFieldData('oxartnum');
    $credentials = new Credentials(
        $username,
        $password,
        $config->getConfigParam('ffAuthPrefix'),
        $config->getConfigParam('ffAuthPostfix')
    );
    $clientBuilder = oxNew(ClientBuilder::class)
        ->withServerUrl($config->getConfigParam('ffServerUrl'))
        ->withCredentials($credentials)
        ->withVersion($config->getConfigParam('ffApiVersion'));
    $adapterFactory = new AdapterFactory(
        $clientBuilder,
        $config->getConfigParam('ffVersion'),
        $config->getConfigParam('ffApiVersion')
    );
    $searchAdapter = $adapterFactory->getSearchAdapter();
    $response = $searchAdapter->records(
        $config->getConfigParam('ffChannel')[Registry::getLang()->getLanguageAbbr()],
        '',
        [
            'idType' => 'productNumber',
            'productNumber' => $recordId,
            'format' => 'json'
        ]
    );

    $record = $response['records'][0] ?? [];

    if ($record === []) {
        return json_encode([]);
    }

    return json_encode([
        'id' => $recordId,
        'record' => $record,
    ]);
}
