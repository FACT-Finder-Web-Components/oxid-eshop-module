<?php

require_once dirname(__FILE__) . '/../../../../bootstrap.php';

define('OX_IS_ADMIN', true);

use Omikron\FactFinder\Oxid\Model\Api\ClientFactory;
use Omikron\FactFinder\Oxid\Model\Api\PushImport;
use Omikron\FactFinder\Oxid\Model\ArticleFeed;
use Omikron\FactFinder\Oxid\Model\Config\FtpParams;
use Omikron\FactFinder\Oxid\Model\Export\FtpClient;

if (!isset($_SERVER["HTTP_HOST"])) {
    parse_str($argv[1], $_GET);
    parse_str($argv[1], $_POST);
}

$articleFeed = new ArticleFeed();
$ftpUploader = new FtpClient(new FtpParams());
$pushImport  = new PushImport(new ClientFactory());

$feed = $articleFeed->generate();
$ftpUploader->upload($feed);
$pushImport->execute();
