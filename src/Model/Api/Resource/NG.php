<?php

declare(strict_types=1);

namespace Omikron\FactFinder\Oxid\Model\Api\Resource;

use Omikron\FactFinder\Oxid\Contract\Api\ClientInterface;
use Omikron\FactFinder\Oxid\Contract\Api\ResourceInterface;
use Omikron\FactFinder\Oxid\Model\Api\Credentials;

class NG implements ResourceInterface
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
        $endpoint = $this->getEndpoint('search', $channel);
        return $this->client->sendRequest($endpoint, ['query' => $term] + $params, $headers + $this->auth());
    }

    /**
     * @inheritDoc
     */
    public function import(string $type, string $channel, array $params = [], array $headers = []): array
    {
        $endpoint = $this->getEndpoint('import', $type);
        $params   = ['channel' => $channel] + $params + ['quiet' => false, 'download' => false];
        return $this->client->postRequest($endpoint, $params, $headers + $this->auth());
    }

    protected function getEndpoint(string $resource, string ...$other): string
    {
        return sprintf('%s/rest/v3/%s/%s', $this->serverUrl, $resource, ...$other);
    }

    protected function auth(): array
    {
        return ['Authorization' => $this->credentials->toBasicAuth()];
    }
}
