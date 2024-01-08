<?php
declare(strict_types=1);

namespace Omikron\FactFinder\Oxid;

use Omikron\FactFinder\Oxid\Core\ViewConfig;
use OxidEsales\Eshop\Application\Controller\Admin\ModuleConfiguration;
use OxidEsales\Eshop\Core\ViewConfig as CoreViewConfig;

$moduleId        = 'ffwebcomponents';
$moduleName      = 'FACT-Finder Web Components';
$companyName     = 'Omikron Data Quality GmbH';
$settingPosition = 0;

$sMetadataVersion = '2.1';

$aModule = [
    'id'          => $moduleId,
    'title'       => "{$moduleName} | {$companyName}",
    'author'      => "{$moduleName} Team",
    'url'         => 'https://web-components.fact-finder.de',
    'description' => "{$moduleName} integration for OXID eShop by {$companyName}",
    'version'     => '5.0.0',
    'thumbnail'   => 'pictures/logo.png',
    'controllers' => [
        'ffWebComponent'             => Component\Widget\WebComponent::class,
        'search_result'              => Controller\SearchResultController::class,
        'test_connection'            => Controller\Admin\TestConnectionController::class,
        'article_feed'               => Controller\Admin\ArticleFeedController::class,
        'http_article_feed'          => Controller\ArticleFeedController::class,
        'http_category_feed'         => Controller\CategoryFeedController::class,
        'http_suggest_category_feed' => Controller\SuggestCategoryFeedController::class,
    ],
    'extend'      => [
        ModuleConfiguration::class => \Omikron\FactFinder\Oxid\Controller\Admin\ModuleConfiguration::class,
        CoreViewConfig::class => ViewConfig::class,
    ],
    'settings'    => [
        [
            'group'    => 'ffMain',
            'name'     => 'ffServerUrl',
            'type'     => 'str',
            'position' => $settingPosition++,
        ],
        [
            'group'    => 'ffMain',
            'name'     => 'ffChannel',
            'type'     => 'aarr',
            'value'    => ['1' => 'de', '2' => 'en'],
            'position' => $settingPosition++,
        ],
        [
            'group'    => 'ffMain',
            'name'     => 'ffUsername',
            'type'     => 'str',
            'position' => $settingPosition++,
        ],
        [
            'group'    => 'ffMain',
            'name'     => 'ffPassword',
            'type'     => 'str',
            'position' => $settingPosition++,
        ],
        [
            'group'    => 'ffMain',
            'name'     => 'ffPublicUsername',
            'type'     => 'str',
            'position' => $settingPosition++,
        ],
        [
            'group'    => 'ffMain',
            'name'     => 'ffPublicPassword',
            'type'     => 'str',
            'position' => $settingPosition++,
        ],
        [
            'group'    => 'ffMain',
            'name'     => 'ffAuthPrefix',
            'type'     => 'str',
            'value'    => 'FACT-FINDER',
            'position' => $settingPosition++,
        ],
        [
            'group'    => 'ffMain',
            'name'     => 'ffAuthPostfix',
            'type'     => 'str',
            'value'    => 'FACT-FINDER',
            'position' => $settingPosition++,
        ],
        [
            'group'       => 'ffMain',
            'name'        => 'ffVersion',
            'type'        => 'select',
            'value'       => '7.3',
            'constraints' => '6.9|6.10|6.11|7.0|7.1|7.2|7.3|ng',
            'position'    => $settingPosition++,
        ],
        [
            'group'       => 'ffMain',
            'name'        => 'ffApiVersion',
            'type'        => 'select',
            'value'       => 'v4',
            'constraints' => 'v4|v5',
            'position'    => $settingPosition++,
        ],
        [
            'name'     => 'ffFieldRoles',
            'type'     => 'str',
            'value'    => '{"brand":"Brand","campaignProductNumber":"ProductNumber","deeplink":"Deeplink","description":"Description","displayProductNumber":"ProductNumber","imageUrl":"ImageUrl","masterArticleNumber":"ProductNumber","price":"Price","productName":"Name","trackingProductNumber":"ProductNumber"}',
            'position' => $settingPosition++,
        ],
        [
            'group'    => 'ffAdvanced',
            'name'     => 'ffUseUrlParams',
            'type'     => 'bool',
            'value'    => true,
            'position' => $settingPosition++,
        ],
        [
            'group'    => 'ffAdvanced',
            'name'     => 'ffAddSearchParams',
            'type'     => 'aarr',
            'value'    => ['add-params' => '', 'add-tracking-params' => ''],
            'position' => $settingPosition++,
        ],
        [
            'group'    => 'ffAdvanced',
            'name'     => 'ffAnonymizeUserId',
            'type'     => 'bool',
            'value'    => true,
            'position' => $settingPosition++,
        ],
        [
            'group'    => 'ffAdvanced',
            'name'     => 'ffUseProxy',
            'type'     => 'bool',
            'value'    => false,
            'position' => $settingPosition++,
        ],
        [
            'group'       => 'ffAdvanced',
            'name'        => 'ffTrackingAddToCartCount',
            'type'        => 'select',
            'value'       => 'count_as_one',
            'constraints' => 'count_as_one|count_selected_amount',
            'position'    => $settingPosition++,
        ],
        [
            'group'    => 'ffAdvanced',
            'name'     => 'ffSidAsUserId',
            'type'     => 'bool',
            'value'    => false,
            'position' => $settingPosition++,
        ],
        [
            'group'    => 'ffFeatures',
            'name'     => 'ffUseForCategories',
            'type'     => 'bool',
            'value'    => false,
            'position' => $settingPosition++,
        ],
        [
            'group'    => 'ffFeatures',
            'name'     => 'ffCampaigns',
            'type'     => 'bool',
            'value'    => false,
            'position' => $settingPosition++,
        ],
        [
            'group'    => 'ffFeatures',
            'name'     => 'ffRecommendations',
            'type'     => 'bool',
            'value'    => false,
            'position' => $settingPosition++,
        ],
        [
            'group'    => 'ffFeatures',
            'name'     => 'ffSimilarProducts',
            'type'     => 'bool',
            'value'    => false,
            'position' => $settingPosition++,
        ],
        [
            'group'    => 'ffFeatures',
            'name'     => 'ffPushedProducts',
            'type'     => 'bool',
            'value'    => false,
            'position' => $settingPosition++,
        ],
        [
            'group'    => 'ffFeatures',
            'name'     => 'ffDisableCache',
            'type'     => 'bool',
            'value'    => false,
            'position' => $settingPosition++,
        ],
        [
            'group'    => 'ffFeed',
            'name'     => 'ffExportAttributes',
            'type'     => 'aarr',
            'value'    => [],
            'position' => $settingPosition++,
        ],
        [
            'group'       => 'ffExport',
            'name'        => 'ffFtpType',
            'position'    => $settingPosition++,
            'type'        => 'select',
            'value'       => 'ftp',
            'constraints' => 'ftp|sftp',
        ],
        [
            'group'    => 'ffExport',
            'name'     => 'ffFtpHost',
            'type'     => 'str',
            'position' => $settingPosition++,
        ],
        [
            'group'    => 'ffExport',
            'name'     => 'ffFtpPort',
            'type'     => 'num',
            'position' => $settingPosition++,
        ],
        [
            'group'    => 'ffExport',
            'name'     => 'ffFtpUser',
            'type'     => 'str',
            'position' => $settingPosition++,
        ],
        [
            'group'    => 'ffExport',
            'name'     => 'ffFtpPassword',
            'type'     => 'str',
            'position' => $settingPosition++,
        ],
        [
            'group'    => 'ffExport',
            'name'     => 'ffFtpKey',
            'type'     => 'str',
            'position' => $settingPosition++,
        ],
        [
            'group'    => 'ffExport',
            'name'     => 'ffFtpKeyPassphrase',
            'type'     => 'password',
            'position' => $settingPosition++,
        ],
        [
            'group'    => 'ffExport',
            'name'     => 'ffFtpRoot',
            'type'     => 'str',
            'value'    => '',
            'position' => $settingPosition++,
        ],
        [
            'group'    => 'ffExport',
            'name'     => 'ffSSLEnabled',
            'type'     => 'bool',
            'value'    => false,
            'position' => $settingPosition++,
        ],
        [
            'group'    => 'ffExport',
            'name'     => 'ffAutomaticImport',
            'type'     => 'bool',
            'value'    => true,
            'position' => $settingPosition++,
        ],
        [
            'group'    => 'ffExport',
            'name'     => 'ffAutomaticImportData',
            'type'     => 'bool',
            'value'    => true,
            'position' => $settingPosition++,
        ],
        [
            'group'    => 'ffExport',
            'name'     => 'ffAutomaticImportSuggest',
            'type'     => 'bool',
            'value'    => false,
            'position' => $settingPosition++,
        ],
        [
            'group'    => 'ffExport',
            'name'     => 'ffAutomaticImportRecommendation',
            'type'     => 'bool',
            'value'    => false,
            'position' => $settingPosition++,
        ],
        [
            'group'    => 'ffExport',
            'name'     => 'ffHTTPExportUser',
            'type'     => 'str',
            'value'    => 'basic_auth_user',
            'position' => $settingPosition++,
        ],
        [
            'group'    => 'ffExport',
            'name'     => 'ffHTTPExportPassword',
            'type'     => 'str',
            'value'    => 'basic_auth_password',
            'position' => $settingPosition++,
        ],
        [
            'group'    => 'ffExport',
            'name'     => 'ffLogPath',
            'type'     => 'str',
            'value'    => '',
            'position' => $settingPosition++,
        ],
        [
            'group'    => 'ffExport',
            'name'     => 'ffIsProceedWhileError',
            'type'     => 'bool',
            'value'    => false,
            'position' => $settingPosition++,
        ],
    ],
];
