<?php

declare(strict_types=1);

namespace Omikron\FactFinder\Oxid\Model\Api;

use Omikron\FactFinder\Oxid\Contract\Api\ClientInterface;
use Omikron\FactFinder\Oxid\Exception\ResponseException;

class TestConnection
{
    /** @var ClientInterface */
    private $apiClient;

    /** @var string */
    private $apiQuery = 'FACT-Finder version';

    public function __construct(ClientFactory $clientFactory)
    {
        $this->apiClient = $clientFactory->create();
    }

    /**
     * @param string $endpoint
     * @param array  $params
     *
     * @throws ResponseException
     */
    public function execute(string $endpoint, array $params)
    {
        $this->apiClient->sendRequest(rtrim($endpoint, '/') . '/Search.ff', $params + ['query' => $this->apiQuery]);
    }
}
