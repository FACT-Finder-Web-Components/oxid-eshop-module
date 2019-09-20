<?php

namespace Omikron\FactFinder\Oxid\Contract\Db;

interface WhereFactoryInterface
{
    public function create(): WhereInterface;
}
