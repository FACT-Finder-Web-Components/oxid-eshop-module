<?php

require_once dirname(__FILE__) . '/../../../../bootstrap.php';

define('OX_IS_ADMIN', true);

use Omikron\FactFinder\Oxid\Model\ArticleFeed;
use Omikron\FactFinder\Oxid\Model\Export\FileWriter;

if (!isset($_SERVER['HTTP_HOST'])) {
    parse_str($argv[1], $_GET);
    parse_str($argv[1], $_POST);
}

$articleFeed = new ArticleFeed();
$fileWriter  = new FileWriter();

$feed = $articleFeed->generate();
$fileWriter->save($feed, $articleFeed->getFileName());
