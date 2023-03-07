<?php

declare(strict_types=1);

namespace Omikron\FactFinder\Oxid\Controller;

use Omikron\FactFinder\Oxid\Export\SuggestCategoryFeed;

class SuggestCategoryFeedController extends ArticleFeedController
{
    protected $exportedType = SuggestCategoryFeed::class;
}
