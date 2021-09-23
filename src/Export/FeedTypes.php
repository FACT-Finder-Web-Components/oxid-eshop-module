<?php

declare(strict_types=1);

namespace Omikron\FactFinder\Oxid\Export;

use InvalidArgumentException;
use Omikron\FactFinder\Oxid\Export\ArticleFeed;
use Omikron\FactFinder\Oxid\Export\CategoryFeed;

class FeedTypes
{
    static protected $feedTypes = [
        'product'  => ArticleFeed::class,
        'category' => CategoryFeed::class,
    ];

    static public function getFeedType($requestedType): string
    {
        if (!isset(self::$feedTypes[$requestedType])) {
            throw new InvalidArgumentException(sprintf('Unknown feed type %s', $requestedType));
        }

        return self::$feedTypes[$requestedType];
    }
}
