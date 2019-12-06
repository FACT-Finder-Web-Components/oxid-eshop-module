<?php

declare(strict_types=1);

namespace Omikron\FactFinder\Oxid\Model\Api;

use Guzzle\Http\Client as HttpClient;
use Guzzle\Http\Exception\ServerErrorResponseException;
use Guzzle\Http\Message\Response;
use Omikron\FactFinder\Oxid\Contract\Api\ClientInterface;
use Omikron\FactFinder\Oxid\Contract\Api\SerializerInterface;
use Omikron\FactFinder\Oxid\Exception\ResponseException;
use Omikron\FactFinder\Oxid\Model\Config\Authorization;

class Client implements ClientInterface
{
    /** @var HttpClient */
    private $client;

    /** @var Authorization */
    private $authorizationParams;

    /** @var SerializerInterface */
    private $serializer;

    public function __construct(SerializerInterface $serializer)
    {
        $this->serializer          = $serializer;
        $this->authorizationParams = new Authorization();
        $this->client              = new HttpClient();
    }

    /**
     * @inheritDoc
     */
    public function sendRequest(string $endpoint, array $params = [], array $headers = []): array
    {
        $params = ['format' => 'json'] + $params;
        $query  = preg_replace('#products%5B\d+%5D%5B(.+?)%5D=#', '\1=', http_build_query($params));
        try {
            $request  = $this->client->get($endpoint . '?' . $query, ['Accept' => 'application/json'] + $headers);
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
        throw new ResponseException((isset($errorMessage['error']) ? $errorMessage['error'] : $errorMessage), $response->getStatusCode());
    }
}
