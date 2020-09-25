<?php

declare(strict_types=1);

namespace Omikron\FactFinder\Oxid\Model\Api\Resource;

use Omikron\FactFinder\Oxid\Contract\Api\ClientInterface;
use Omikron\FactFinder\Oxid\Contract\Api\ResourceInterface;

class NG implements ResourceInterface
{
    /** @var ClientInterface */
    protected $client;

    public function __construct(ClientInterface $client)
    {
        $this->client = $client;
    }

    /**
     * @inheritDoc
     */
    public function search(string $term, string $channel, array $params = []): array
    {
        $endpoint = $this->getEndpoint('search', $channel);
        return $this->client->sendRequest($endpoint, ['query' => $term] + $params);
    }

    /**
     * @inheritDoc
     */
    public function import(string $type, string $channel, array $params = []): array
    {
        $endpoint = $this->getEndpoint('import', $type);
        $params   = ['channel' => $channel] + $params + ['quiet' => false, 'download' => false];
        return $this->client->postRequest($endpoint, $params);
    }

    protected function getEndpoint(string $resource, string ...$other): string
    {
        return sprintf('rest/v3/%s/%s', $resource, ...$other);
    }
}
