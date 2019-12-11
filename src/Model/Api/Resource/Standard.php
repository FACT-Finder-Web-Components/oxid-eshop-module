<?php

declare(strict_types=1);

namespace Omikron\FactFinder\Oxid\Model\Api\Resource;

use Omikron\FactFinder\Oxid\Contract\Api\ClientInterface;
use Omikron\FactFinder\Oxid\Contract\Api\ResourceInterface;
use Omikron\FactFinder\Oxid\Model\Api\Credentials;

class Standard implements ResourceInterface
{
    /** @var string */
    protected $serverUrl;

    /** @var ClientInterface */
    protected $client;

    /** @var Credentials */
    protected $credentials;

    public function __construct(string $serverUrl, ClientInterface $client, Credentials $credentials)
    {
        $this->serverUrl   = $serverUrl;
        $this->client      = $client;
        $this->credentials = $credentials;
    }

    /**
     * @inheritDoc
     */
    public function search(string $term, string $channel, array $params = [], array $headers = []): array
    {
        $params = ['channel' => $channel, 'query' => $term] + $params + $this->credentials->toArray();
        return $this->client->sendRequest($this->getEndpoint('search'), $params, $headers);
    }

    /**
     * @inheritDoc
     */
    public function import(string $type, string $channel, array $params = [], array $headers = []): array
    {
        $params = ['type' => str_replace('search', 'data', $type), 'channel' => $channel]
            + $params
            + ['quiet' => true, 'download' => true]
            + $this->credentials->toArray();
        return $this->client->sendRequest($this->getEndpoint('import'), $params, $headers);
    }

    protected function getEndpoint(string $resource): string
    {
        return sprintf('%s/%s.ff', $this->serverUrl, ucfirst($resource));
    }
}
