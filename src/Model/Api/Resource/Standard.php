<?php

declare(strict_types=1);

namespace Omikron\FactFinder\Oxid\Model\Api\Resource;

use Omikron\FactFinder\Oxid\Contract\Api\ClientInterface;
use Omikron\FactFinder\Oxid\Contract\Api\ResourceInterface;

class Standard implements ResourceInterface
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
        $params = ['channel' => $channel, 'query' => $term, 'format' => 'json'] + $params;
        return $this->client->sendRequest('Search.ff', $params);
    }

    /**
     * @inheritDoc
     */
    public function import(string $type, string $channel, array $params = []): array
    {
        $params = ['type' => str_replace('search', 'data', $type), 'channel' => $channel]
            + $params
            + ['quiet' => true, 'download' => true, 'format' => 'json'];
        return $this->client->sendRequest('Import.ff', $params);
    }
}
