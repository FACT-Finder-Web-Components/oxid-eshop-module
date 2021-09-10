<?php

declare(strict_types=1);

namespace Omikron\FactFinder\Oxid\Controller;

use Omikron\FactFinder\Oxid\Export\ArticleFeed;
use Omikron\FactFinder\Oxid\Export\CategoryFeed;

trait FeedExportTrait
{
    public function getFeedTypes(): array
    {
        return [
            'product'  => ArticleFeed::class,
            'category' => CategoryFeed::class,
        ];
    }

    public function getFeedType($requestedType): string
    {
        $feedTypes = $this->getFeedTypes();
        if (!isset($feedTypes[$requestedType])) {
            throw new \Exception(sprintf('Unknown feed type %s', $requestedType));
        }

        return $feedTypes[$requestedType];
    }
}
