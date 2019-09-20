<?php

namespace Omikron\FactFinder\Oxid\Contract\Export;

interface DataProviderInterface
{
    /**
     * Iterate trough entities to be exported
     *
     * @return iterable
     */
    public function getEntities(): iterable;
}
