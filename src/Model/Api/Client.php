<?php

declare(strict_types=1);

namespace Omikron\FactFinder\Oxid\Model\Api;

use Guzzle\Http\Client as HttpClient;
use Guzzle\Http\Exception\ServerErrorResponseException;
use Guzzle\Http\Message\RequestInterface;
use Guzzle\Http\Message\Response;
use Omikron\FactFinder\Oxid\Contract\Api\ClientInterface;
use Omikron\FactFinder\Oxid\Contract\Api\SerializerInterface;
use Omikron\FactFinder\Oxid\Exception\ResponseException;

class Client implements ClientInterface
{
    /** @var SerializerInterface */
    private $serializer;

    public function __construct(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }

    protected function client(): HttpClient
    {
        return new HttpClient();
    }

    /**
     * @inheritDoc
     */
    public function sendRequest(string $endpoint, array $params = [], array $headers = []): array
    {
        return $this->send($this->client()->get(), $endpoint, $params, $headers);
    }

    /**
     * @inheritDoc
     */
    public function postRequest(string $endpoint, array $params, array $headers = []): array
    {
        return $this->send($this->client()->post(), $endpoint, $params, $headers);
    }

    protected function send(RequestInterface $request, string $endpoint, array $params, array $headers)
    {
        try {
            $params = ['format' => 'json'] + $params;
            $query  = preg_replace('#products%5B\d+%5D%5B(.+?)%5D=#', '\1=', http_build_query($params));

            $request->setUrl($endpoint . '?' . $query);
            $request->setHeaders(['Accept' => 'application/json'] + $headers);
            $response = $request->send();
            if ($response->isSuccessful()) {
                return $this->serializer->unserialize((string) $response->getBody());
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
        $errorMessage = current($this->serializer->unserialize((string) $response->getBody()));
        throw new ResponseException($errorMessage['error'] ?? $errorMessage, $response->getStatusCode());
    }
}
