<?php

declare(strict_types=1);

namespace Omikron\FactFinder\Oxid\Model\Api;

use Omikron\FactFinder\Oxid\Contract\Api\ClientInterface;
use Omikron\FactFinder\Oxid\Model\Api\Serializer\Json;

class ClientFactory
{
    public function create(): ClientInterface
    {
        return new Client(new Json());
    }
}
