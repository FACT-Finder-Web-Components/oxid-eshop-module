<?php

namespace Omikron\FactFinder\Oxid\Contract\Db;

interface SelectFactoryInterface
{
    public function create(): SelectInterface;
}
