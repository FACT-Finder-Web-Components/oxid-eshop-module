<?php

declare(strict_types=1);

namespace Omikron\FactFinder\Oxid\Export;

use InvalidArgumentException;

class FeedTypes
{
    protected static $feedTypes = [
        'product'  => ArticleFeed::class,
        'category' => CategoryFeed::class,
    ];

    public static function getFeedType($requestedType): string
    {
        if (!isset(self::$feedTypes[$requestedType])) {
            throw new InvalidArgumentException(sprintf('Unknown feed type %s', $requestedType));
        }

        return self::$feedTypes[$requestedType];
    }
}
