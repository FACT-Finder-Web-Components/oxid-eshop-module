<?php

$options = getopt('s:l:');
$shopId  = $options['s'] ?? 0;
if (!$shopId) {
    throw new RuntimeException('Please specify the shop ID using the "s" parameter!');
}
$languageId = $options['l'] ?? 0;

require_once dirname(__FILE__) . '/../../../../bootstrap.php';

define('OX_IS_ADMIN', true);

use Omikron\FactFinder\Oxid\Export\ProductFeed;
use Omikron\FactFinder\Oxid\Export\Stream\Csv;
use OxidEsales\Eshop\Core\Config;
use OxidEsales\Eshop\Core\Registry;

Registry::getConfig()->setShopId($shopId);
Registry::set(Config::class, null);
Registry::getLang()->setBaseLanguage($languageId);

$feed = oxNew(ProductFeed::class);
$feed->generate(oxNew(Csv::class, STDOUT));
