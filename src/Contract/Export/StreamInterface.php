<?php

namespace Omikron\FactFinder\Oxid\Contract\Export;

interface StreamInterface
{
    /**
     * @param array $entity
     */
    public function addEntity(array $entity);
}
