<?php

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
use Omikron\FactFinder\Oxid\Model\Api\PushImport;
use Omikron\FactFinder\Oxid\Model\Config\FtpParams;
use Omikron\FactFinder\Oxid\Model\Export\FtpClient;
use OxidEsales\Eshop\Core\Config;
use OxidEsales\Eshop\Core\Registry;
use Omikron\FactFinder\Oxid\Controller\ArticleFeedController;

try {
    $feedFQN = (new ArticleFeedController())->getFeedType($exportType);
    Registry::getConfig()->setShopId($shopId);
    Registry::set(Config::class, null);
    Registry::getLang()->setBaseLanguage($languageId);

    $articleFeed = oxNew($feedFQN);
    $ftpUploader = oxNew(FtpClient::class, oxNew(FtpParams::class));
    $pushImport  = oxNew(PushImport::class);

    $handle = tmpfile();
    $articleFeed->generate(oxNew(Csv::class, $handle));
    $ftpUploader->upload($handle, $articleFeed->getFileName());
    $pushImport->execute();
} finally {
    fclose($handle);
}
