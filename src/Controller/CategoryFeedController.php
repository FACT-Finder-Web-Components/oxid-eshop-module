<?php

declare(strict_types=1);

namespace Omikron\FactFinder\Oxid\Controller;

use Omikron\FactFinder\Oxid\Export\CategoryFeed;
use Omikron\FactFinder\Oxid\Export\FeedTypes;
use Omikron\FactFinder\Oxid\Export\Stream\Csv;
use Omikron\FactFinder\Oxid\Model\Export\Http\Authentication;
use OxidEsales\Eshop\Application\Controller\FrontendController;
use OxidEsales\Eshop\Core\Registry;
use OxidEsales\Eshop\Core\Request;

class CategoryFeedController extends ArticleFeedController
{
    protected $exportedType = CategoryFeed::class;
}
