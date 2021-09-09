<?php

declare(strict_types=1);

namespace Omikron\FactFinder\Oxid\Export\Entity;

interface DataProviderInterface
{
    /**
     * Iterate trough entities to be exported.
     *
     * @return ExportEntityInterface[]
     */
    public function getEntities(): iterable;
}
