<?php

declare(strict_types=1);

namespace Omikron\FactFinder\Oxid\Model\Api;

use Guzzle\Http\Client as HttpClient;
use Guzzle\Http\Exception\ServerErrorResponseException;
use Guzzle\Http\Message\Response;
use Omikron\FactFinder\Oxid\Contract\Api\ClientInterface;
use Omikron\FactFinder\Oxid\Exception\ResponseException;

class Client implements ClientInterface
{
    /** @var HttpClient */
    protected $httpClient;

    public function __construct(HttpClient $httpClient)
    {
        $this->httpClient = $httpClient;
    }

    /**
     * @inheritDoc
     */
    public function sendRequest(string $endpoint, array $params = []): array
    {
        return $this->send('GET', $this->getUri($endpoint, $params));
    }

    /**
     * @inheritDoc
     */
    public function postRequest(string $endpoint, array $params): array
    {
        return $this->send('POST', $this->getUri($endpoint, $params));
    }

    protected function getUri(string $endpoint, array $params): string
    {
        $query = preg_replace('#products%5B\d+%5D%5B(.+?)%5D=#', '\1=', http_build_query($params));
        return $endpoint . '?' . $query;
    }

    protected function send(string $method, string $uri)
    {
        try {
            $response = $this->httpClient->createRequest($method, $uri)->send();
            if ($response->isSuccessful()) {
                return (array) $response->json();
            }

            $this->badRequest($response);
        } catch (ServerErrorResponseException $e) {
            $this->badRequest($e->getResponse());
        } catch (\Exception $e) {
            throw new ResponseException($e->getMessage()); // When request didn't take place
        }
    }

    protected function badRequest(Response $response)
    {
        $errorMessage = current((array) $response->json());
        throw new ResponseException($errorMessage['error'] ?? $errorMessage, $response->getStatusCode());
    }
}
