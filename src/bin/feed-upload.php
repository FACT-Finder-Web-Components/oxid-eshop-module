<?php

$options = getopt('s:');
$shopId  = $options['s'] ?? 0;
if (!$shopId) {
    throw new RuntimeException('Please specify the shop ID using the "s" parameter!');
}

require_once dirname(__FILE__) . '/../../../../bootstrap.php';

define('OX_IS_ADMIN', true);

use Omikron\FactFinder\Oxid\Model\Api\ClientFactory;
use Omikron\FactFinder\Oxid\Model\Api\PushImport;
use Omikron\FactFinder\Oxid\Model\ArticleFeed;
use Omikron\FactFinder\Oxid\Model\Config\FtpParams;
use Omikron\FactFinder\Oxid\Model\Export\FtpClient;
use OxidEsales\Eshop\Core\Config;
use OxidEsales\Eshop\Core\Registry;

try {
    Registry::getConfig()->setShopId($shopId);
    Registry::set(Config::class, null);

    $articleFeed = oxNew(ArticleFeed::class);
    $ftpUploader = oxNew(FtpClient::class, oxNew(FtpParams::class));
    $pushImport  = oxNew(PushImport::class, oxNew(ClientFactory::class));

    $handle = tmpfile();
    $articleFeed->generate($handle);
    $ftpUploader->upload($handle, $articleFeed->getFileName());
    $pushImport->execute();
} finally {
    fclose($handle);
}
