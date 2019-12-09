<?php

namespace Omikron\FactFinder\Oxid\Contract\Api;

use Omikron\FactFinder\Oxid\Exception\ResponseException;

interface ClientInterface
{
    /**
     * Sends HTTP GET request to FACT-Finder. Returns the parsed server response.
     *
     * @param string $endpoint
     * @param array  $params
     * @param array  $headers
     *
     * @return array
     * @throws ResponseException
     */
    public function sendRequest(string $endpoint, array $params, array $headers = []): array;

    /**
     * Sends HTTP POST request to FACT-Finder. Returns the parsed server response.
     *
     * @param string $endpoint
     * @param array  $params
     * @param array  $headers
     * @param string $method
     *
     * @return array
     * @throws ResponseException
     */
    public function postRequest(string $endpoint, array $params, array $headers = []): array;
}
