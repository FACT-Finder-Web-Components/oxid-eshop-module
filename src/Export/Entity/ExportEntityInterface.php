<?php

declare(strict_types=1);

namespace Omikron\FactFinder\Oxid\Export\Entity;

interface ExportEntityInterface
{
    public function toArray(): array;
}
