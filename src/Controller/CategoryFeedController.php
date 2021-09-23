<?php

declare(strict_types=1);

namespace Omikron\FactFinder\Oxid\Controller;

use Omikron\FactFinder\Oxid\Export\CategoryFeed;

class CategoryFeedController extends ArticleFeedController
{
    protected $exportedType = CategoryFeed::class;
}
