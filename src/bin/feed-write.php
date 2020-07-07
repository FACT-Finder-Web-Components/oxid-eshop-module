<?php

require_once dirname(__FILE__) . '/../../../../bootstrap.php';

define('OX_IS_ADMIN', true);

use Omikron\FactFinder\Oxid\Model\ArticleFeed;
use Omikron\FactFinder\Oxid\Model\Export\FileWriter;

if (!isset($_SERVER['HTTP_HOST']) && $argc > 1) {
    parse_str($argv[1], $_GET);
    parse_str($argv[1], $_POST);
}

$articleFeed = new ArticleFeed();
$fileWriter  = new FileWriter();

try {
    $handle = fopen(OX_BASE_PATH . 'export/' . $articleFeed->getFileName(), 'w');
    $articleFeed->generate($handle);
} finally {
    fclose($handle);
}
