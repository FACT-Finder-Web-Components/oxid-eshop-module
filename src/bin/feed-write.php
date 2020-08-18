<?php

$options = getopt('s:');
$shopId  = $options['s'] ?? 0;
if (!$shopId) {
    throw new RuntimeException('Please specify the shop ID using the "s" parameter!');
}

require_once dirname(__FILE__) . '/../../../../bootstrap.php';

define('OX_IS_ADMIN', true);

use Omikron\FactFinder\Oxid\Export\ArticleFeed;
use Omikron\FactFinder\Oxid\Export\Stream\Csv;
use OxidEsales\Eshop\Core\Config;
use OxidEsales\Eshop\Core\Registry;

Registry::getConfig()->setShopId($shopId);
Registry::set(Config::class, null);

$feed = oxNew(ArticleFeed::class);
$feed->generate(oxNew(Csv::class, STDOUT));
