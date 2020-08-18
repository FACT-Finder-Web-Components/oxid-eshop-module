<?php

namespace Omikron\FactFinder\Oxid\Export\Stream;

interface StreamInterface
{
    /**
     * @param array $entity
     */
    public function addEntity(array $entity): void;
}
