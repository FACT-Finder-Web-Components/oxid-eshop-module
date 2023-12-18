<?php

declare(strict_types=1);

namespace Omikron\FactFinder\Oxid\Controller;

use Exception;
use Omikron\FactFinder\Communication\Client\ClientBuilder;
use Omikron\FactFinder\Communication\Credentials;
use OxidEsales\Eshop\Application\Controller\FrontendController;
use OxidEsales\Eshop\Core\Registry;
use Psr\Http\Message\ResponseInterface;

class SearchResultController extends FrontendController
{
    public function __construct()
    {
        parent::__construct();

        $this->_sThisTemplate = '@ffwebcomponents/webcomponents/blocks/page/result.html.twig';
    }

    public function proxy(): void
    {
        $currentUrl = Registry::getUtilsUrl()->getCurrentUrl();
        $endpoint   = $this->getEndpoint(Registry::getUtilsUrl()->getCurrentUrl());
        $httpMethod = Registry::getUtilsServer()->getServerVar()['REQUEST_METHOD'];

        try {
            if (!$endpoint) {
                throw new Exception('Endpoint missing');
            }

            $client = oxNew(ClientBuilder::class)
                ->withServerUrl($this->getConfigParam('ffServerUrl'))
                ->withCredentials($this->getCredentials())
                ->withVersion($this->getConfigParam('ffVersion'))
                ->build();

            switch ($httpMethod) {
                case 'GET':
                    $query = (string) $this->removeOxidParams(parse_url($currentUrl, PHP_URL_QUERY));
                    $this->showJsonAndExit($this->unwrapResponse($client->request('GET', $endpoint . '?' . $query)));

                    break;
                case 'POST':
                    $this->showJsonAndExit($this->unwrapResponse($client->request('POST', $endpoint, [
                        'body'    => $this->getRequest()->getContent(),
                        'headers' => ['Content-Type' => 'application/json'],
                    ])));

                    break;
                default:
                    throw new Exception(sprintf('HTTP Method %s is not supported', $httpMethod));
            }
        } catch (Exception $e) {
            $this->fallback();
            echo json_encode(['error' => $e->getMessage()]);
        }
    }

    protected function showJsonAndExit(string $jsonResponse): void
    {
        $oUtils   = Registry::getUtils();
        $oUtils->setHeader('Content-Type: application/json');
        header('Content-Type: application/json');
        $oUtils->showMessageAndExit($jsonResponse);
    }

    protected function fallback(): void
    {
        //this function could be used to implement fallback logic in case of any communication error.
        $this->showJsonAndExit('');
    }

    protected function getCredentials(): Credentials
    {
        return new Credentials(...array_map([$this, 'getConfigParam'], ['ffUsername', 'ffPassword', 'ffAuthPrefix', 'ffAuthPostfix']));
    }

    protected function getConfigParam(string $key)
    {
        return Registry::getConfig()->getConfigParam($key);
    }

    private function getEndpoint(string $currentUrl): string
    {
        preg_match('#/([A-Za-z]+\.ff|rest/v[^?]*)#', $currentUrl, $match);
        return $match[1] ?? '';
    }

    private function removeOxidParams(string $queryString): string
    {
        return preg_replace('/(fnc|cl)=[A-Za-z0-9_]*&?/', '', $queryString);
    }

    private function unwrapResponse(ResponseInterface $response): string
    {
        return $response->getBody()->getContents();
    }
}
