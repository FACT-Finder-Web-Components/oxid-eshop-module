<?php

declare(strict_types=1);

namespace Omikron\FactFinder\Oxid\Export\Data;

interface CollectionInterface
{
    public function getEntity(): string;
}
