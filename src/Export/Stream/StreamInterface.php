<?php

declare(strict_types=1);

namespace Omikron\FactFinder\Oxid\Export\Stream;

interface StreamInterface
{
    /**
     * @param array $entity
     */
    public function addEntity(array $entity): void;
}
