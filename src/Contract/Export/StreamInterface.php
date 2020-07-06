<?php

namespace Omikron\FactFinder\Oxid\Contract\Export;

interface StreamInterface
{
    /**
     * @param array $entity
     *
     * @return resource
     */
    public function addEntity(array $entity);
}
