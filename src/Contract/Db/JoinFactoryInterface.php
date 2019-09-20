<?php

namespace Omikron\FactFinder\Oxid\Contract\Db;

interface JoinFactoryInterface
{
    public function create(): JoinInterface;
}
