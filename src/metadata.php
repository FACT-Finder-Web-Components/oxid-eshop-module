<?php

namespace Omikron\FactFinder\Oxid;

use OxidEsales\Eshop\Application\Controller\Admin\ModuleConfiguration;

$moduleId        = 'ffwebcomponents';
$moduleName      = 'FACT-Finder<sup>&reg;</sup> Web Components';
$companyName     = 'Omikron Data Quality GmbH';
$settingPosition = 0;

$sMetadataVersion = '2.1';

$aModule = [
    'id'          => $moduleId,
    'title'       => "{$moduleName} | {$companyName}",
    'author'      => "{$moduleName} Team",
    'url'         => 'https://web-components.fact-finder.de',
    'description' => "{$moduleName} integration for OXID eShop by {$companyName}",
    'version'     => '0.0.1',
    'thumbnail'   => 'out/pictures/logo.png',
    'controllers' => [
        'ffWebComponent'    => Component\Widget\WebComponent::class,
        'search_result'     => Controller\SearchResultController::class,
        'test_connection'   => Controller\Admin\TestConnectionController::class,
        'article_feed'      => Controller\Admin\ArticleFeedController::class,
        'http_article_feed' => Controller\ArticleFeedController::class,
    ],
    'blocks'      => [
        [
            'template' => 'module_config.tpl',
            'block'    => 'admin_module_config_form',
            'file'     => 'views/admin/blocks/factfinder_module_config.tpl',
        ],
        [
            'template' => 'module_config.tpl',
            'block'    => 'admin_module_config_var_type_aarr',
            'file'     => 'views/admin/blocks/factfinder_config_field_channel.tpl',
        ],
        [
            'template' => 'module_config.tpl',
            'block'    => 'admin_module_config_var_type_aarr',
            'file'     => 'views/admin/blocks/factfinder_config_field_attributes.tpl',
        ],
        [
            'template' => 'layout/base.tpl',
            'block'    => 'head_css',
            'file'     => 'views/frontend/blocks/scripts.tpl',
        ],
        [
            'template' => 'layout/page.tpl',
            'block'    => 'layout_header',
            'file'     => 'views/frontend/blocks/init.tpl',
        ],
        [
            'template' => 'layout/sidebar.tpl',
            'block'    => 'sidebar_categoriestree',
            'file'     => 'views/frontend/blocks/category/sidebar.tpl',
        ],
        [
            'template' => 'page/checkout/basket.tpl',
            'block'    => 'checkout_basket_main',
            'file'     => 'views/frontend/blocks/campaign/cart.tpl',
        ],
        [
            'template' => 'page/checkout/thankyou.tpl',
            'block'    => 'checkout_thankyou_main',
            'file'     => 'views/frontend/blocks/order/tracking.tpl',
        ],
        [
            'template' => 'page/details/inc/related_products.tpl',
            'block'    => 'details_relatedproducts_crossselling',
            'file'     => 'views/frontend/blocks/campaign/product.tpl',
        ],
        [
            'template' => 'page/list/list.tpl',
            'block'    => 'page_list_listbody',
            'file'     => 'views/frontend/blocks/category/list.tpl',
        ],
        [
            'template' => 'widget/header/search.tpl',
            'block'    => 'widget_header_search_form',
            'file'     => 'views/frontend/widget/search.tpl',
        ],
    ],
    'extend'      => [
        ModuleConfiguration::class => \Omikron\FactFinder\Oxid\Controller\Admin\ModuleConfiguration::class,
    ],
    'templates'   => [
        'admin/page/ajax_result.tpl'      => 'ff/ffwebcomponents/views/admin/ajax_result.tpl',
        'page/factfinder/result.tpl'      => 'ff/ffwebcomponents/views/frontend/page/result.tpl',
        'ff/asn.tpl'                      => 'ff/ffwebcomponents/views/frontend/widget/asn.tpl',
        'ff/breadcrumbs.tpl'              => 'ff/ffwebcomponents/views/frontend/widget/breadcrumbs.tpl',
        'ff/campaign/feedbacktext.tpl'    => 'ff/ffwebcomponents/views/frontend/widget/campaign/feedbacktext.tpl',
        'ff/campaign/pushed_products.tpl' => 'ff/ffwebcomponents/views/frontend/widget/campaign/pushed_products.tpl',
        'ff/communication.tpl'            => 'ff/ffwebcomponents/views/frontend/widget/communication.tpl',
        'ff/recommendation.tpl'           => 'ff/ffwebcomponents/views/frontend/widget/recommendation.tpl',
        'ff/record_list.tpl'              => 'ff/ffwebcomponents/views/frontend/widget/record_list.tpl',
        'ff/paging.tpl'                   => 'ff/ffwebcomponents/views/frontend/widget/paging.tpl',
        'ff/ppp.tpl'                      => 'ff/ffwebcomponents/views/frontend/widget/ppp.tpl',
        'ff/search/result.tpl'            => 'ff/ffwebcomponents/views/frontend/blocks/search/result.tpl',
        'ff/similar.tpl'                  => 'ff/ffwebcomponents/views/frontend/widget/similar.tpl',
        'ff/result_count.tpl'             => 'ff/ffwebcomponents/views/frontend/widget/result_count.tpl',
        'ff/sortbox.tpl'                  => 'ff/ffwebcomponents/views/frontend/widget/sortbox.tpl',
        'ff/suggest.tpl'                  => 'ff/ffwebcomponents/views/frontend/widget/suggest.tpl',
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
            'name'        => 'ffApiVersion',
            'type'        => 'select',
            'value'       => '7.3',
            'constraints' => '6.9|6.10|6.11|7.0|7.1|7.2|7.3|ng',
            'position'    => $settingPosition++,
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
            'group'    => 'ffFeed',
            'name'     => 'ffExportAttributes',
            'type'     => 'aarr',
            'position' => $settingPosition++,
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
            'type'     => 'str',
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
    ],

    'smartyPluginDirectories' => ['views/smarty'],
];
