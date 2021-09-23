<?php

use \Omikron\FactFinder\Oxid\Export\FeedTypes;

$options = getopt('s:t:l:');
$shopId  = $options['s'] ?? 0;
$exportType = $options['t'] ?? 'product';

if (!$shopId) {
    throw new RuntimeException('Please specify the shop ID using the "s" parameter!');
}

$languageId = $options['l'] ?? 0;

require_once dirname(__FILE__) . '/../../../../bootstrap.php';

define('OX_IS_ADMIN', true);

use Omikron\FactFinder\Oxid\Export\Stream\Csv;
use OxidEsales\Eshop\Core\Config;
use OxidEsales\Eshop\Core\Registry;
use Omikron\FactFinder\Oxid\Controller\ArticleFeedController;

Registry::getConfig()->setShopId($shopId);
Registry::set(Config::class, null);
Registry::getLang()->setBaseLanguage($languageId);

$feedFQN = FeedTypes::getFeedType($exportType);
$feed = oxNew($feedFQN);
$feed->generate(oxNew(Csv::class, STDOUT));
