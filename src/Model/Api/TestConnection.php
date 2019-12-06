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

    /** @var string */
    private $version;

    public function __construct(ClientInterface $client, string $version)
    {
        $this->apiClient = $client;
        $this->version   = $version;
    }

    /**
     * @param string $endpoint
     * @param array  $params
     *
     * @throws ResponseException
     */
    public function execute(string $serverUrl, string $channel, Credentials $credentials)
    {
        switch ($this->version) {
            case 'NG':
                $endpoint = sprintf('%s/rest/v3/search/%s', rtrim($serverUrl), $channel);
                $this->apiClient->sendRequest($endpoint, ['query' => $this->apiQuery], [
                    'Authorization' => 'Basic ' . $credentials->toBasicAuth(),
                ]);
                break;

            default:
                $params = ['channel' => $channel, 'query' => $this->apiClient] + $credentials->toArray();
                $this->apiClient->sendRequest(rtrim($serverUrl, '/') . '/Search.ff', $params);
                break;
        }
    }
}
