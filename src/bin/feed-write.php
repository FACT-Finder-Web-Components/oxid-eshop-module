<?php

$options = getopt('s:');
$shopId  = $options['s'] ?? 0;
if (!$shopId) {
    throw new RuntimeException('Please specify the shop ID using the "s" parameter!');
}

require_once dirname(__FILE__) . '/../../../../bootstrap.php';

define('OX_IS_ADMIN', true);

use Omikron\FactFinder\Oxid\Model\ArticleFeed;
use OxidEsales\Eshop\Core\Config;
use OxidEsales\Eshop\Core\Registry;

try {
    Registry::getConfig()->setShopId($shopId);
    Registry::set(Config::class, null);

    $articleFeed = oxNew(ArticleFeed::class);
    $fileHandle  = fopen(OX_BASE_PATH . 'export/' . $articleFeed->getFileName(), 'w');
    $articleFeed->generate($fileHandle);
} finally {
    fclose($fileHandle);
}
