<?php

namespace Omikron\FactFinder\Oxid\Contract\Config;

interface ParametersSourceInterface
{
    /**
     * Get configuration parameters for the <ff-communication> component
     *
     * @return array
     */
    public function getParameters(): array;
}
