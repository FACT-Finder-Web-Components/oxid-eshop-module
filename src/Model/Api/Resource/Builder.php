<?php

declare(strict_types=1);

namespace Omikron\FactFinder\Oxid\Model\Api\Resource;

use Guzzle\Http\Client as HttpClient;
use Omikron\FactFinder\Oxid\Contract\Api\ClientInterface;
use Omikron\FactFinder\Oxid\Contract\Api\ResourceInterface;
use Omikron\FactFinder\Oxid\Model\Api\Client;
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

    public function withServerUrl(string $serverUrl): Builder
    {
        $this->serverUrl = trim($serverUrl);
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
        $client = oxNew(Client::class, $this->createHttpClient());
        return oxNew($this->apiVersion === 'ng' ? NG::class : Standard::class, $client);
    }

    protected function createHttpClient(): HttpClient
    {
        $httpClient = new HttpClient($this->serverUrl, [HttpClient::REQUEST_OPTIONS => [
            'headers' => ['Accept' => 'application/json'],
        ]]);
        return $httpClient->addSubscriber(new Authenticator($this->apiVersion, $this->credentials));
    }
}
