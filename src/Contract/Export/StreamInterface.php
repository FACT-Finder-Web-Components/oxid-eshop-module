<?php

namespace Omikron\FactFinder\Oxid\Contract\Export;

use SplFileObject as File;

interface StreamInterface
{
    public function addEntity(array $entity): File;
}
