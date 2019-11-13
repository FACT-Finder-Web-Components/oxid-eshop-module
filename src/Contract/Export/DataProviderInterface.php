<?php

namespace Omikron\FactFinder\Oxid\Contract\Export;

use Iterator;

interface DataProviderInterface
{
    /**
     * Iterate trough entities to be exported
     *
     * @return Iterator
     */
    public function getEntities(): Iterator;
}
