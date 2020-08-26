<?php

declare(strict_types=1);

namespace Omikron\FactFinder\Oxid\Model\Api\Resource;

use Omikron\FactFinder\Oxid\Contract\Api\ClientInterface;
use Omikron\FactFinder\Oxid\Contract\Api\ResourceInterface;
use Omikron\FactFinder\Oxid\Model\Api\Credentials;

class Builder
{
    /** @var ClientInterface */
    protected $client;

    /** @var Credentials */
    protected $credentials;

    /** @var string */
    protected $serverUrl;

    /** @var string */
    protected $apiVersion = '7.3';

    public function withClient(ClientInterface $client): Builder
    {
        $this->client = $client;
        return $this;
    }

    public function withServerUrl(string $serverUrl): Builder
    {
        $this->serverUrl = rtrim($serverUrl);
        return $this;
    }

    public function withCredentials(Credentials $credentials): Builder
    {
        $this->credentials = $credentials;
        return $this;
    }

    public function withApiVersion(string $apiVersion): Builder
    {
        $this->apiVersion = $apiVersion;
        return $this;
    }

    public function build(): ResourceInterface
    {
        $params = [$this->serverUrl, $this->client, $this->credentials];
        return oxNew($this->apiVersion === 'ng' ? NG::class : Standard::class, ...$params);
    }
}
