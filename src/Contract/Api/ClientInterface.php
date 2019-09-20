<?php

namespace Omikron\FactFinder\Oxid\Contract\Api;

use Omikron\Factfinder\Oxid\Exception\ResponseException;

interface ClientInterface
{
    /**
     * Sends HTTP GET request to FACT-Finder. Returns the parsed server response.
     *
     * @param string $endpoint
     * @param array  $params
     *
     * @return array
     * @throws ResponseException
     */
    public function sendRequest(string $endpoint, array $params): array;
}
