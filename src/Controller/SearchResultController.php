<?php

declare(strict_types=1);

namespace Omikron\FactFinder\Oxid\Controller;

use Omikron\FactFinder\Communication\Client\ClientBuilder;
use Omikron\FactFinder\Communication\Version;
use Omikron\FactFinder\Oxid\Event\EnrichProxyDataEvent;
use Omikron\FactFinder\Oxid\Subscriber\EnrichProxyDataEventSubscriber;
use OxidEsales\Eshop\Application\Controller\FrontendController;
use OxidEsales\Eshop\Core\Registry;
use OxidEsales\EshopCommunity\Internal\Container\ContainerFactory;
use OxidEsales\EshopCommunity\Internal\Framework\Module\Facade\ModuleSettingServiceInterface;
use Symfony\Component\EventDispatcher\EventDispatcher;

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
                throw new \Exception('Endpoint missing');
            }

            $client = oxNew(ClientBuilder::class)
                ->withServerUrl($this->getConfigParam('ffServerUrl'))
                ->withApiKey($this->getConfigParam('ffApiKey'))
                ->withVersion(Version::NG)
                ->build();

            switch ($httpMethod) {
                case 'GET':
                    $query    = (string) $this->removeOxidParams(parse_url($currentUrl, PHP_URL_QUERY));
                    $response = $client->request('GET', $endpoint . '?' . $query);

                    break;
                case 'POST':
                    $rawBody = file_get_contents('php://input');
                    $body    = json_decode($rawBody, true) ?: [];

                    if (json_last_error() !== JSON_ERROR_NONE) {
                        throw new \Exception('Invalid JSON in request body');
                    }

                    $response = $client->request('POST', $endpoint, [
                        'body'    => json_encode($body),
                        'headers' => ['Content-Type' => 'application/json'],
                    ]);

                    break;
                default:
                    throw new \Exception(sprintf('HTTP Method %s is not supported', $httpMethod));
            }
            $eventDispatcher = new EventDispatcher();
            $eventDispatcher->addSubscriber(new EnrichProxyDataEventSubscriber());
            $event = new EnrichProxyDataEvent(json_decode($response->getBody()->getContents(), true) ?? []);
            $eventDispatcher->dispatch($event, EnrichProxyDataEvent::class);
            $this->showJsonAndExit(json_encode($event->getData()));
        } catch (\Exception $e) {
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
        // this function could be used to implement fallback logic in case of any communication error.
        $this->showJsonAndExit('Error: Unable to process the request.');
    }

    protected function getConfigParam(string $key): string
    {
        $moduleSettingService = ContainerFactory::getInstance()
            ->getContainer()
            ->get(ModuleSettingServiceInterface::class);

        return (string) $moduleSettingService->getString($key, 'ffwebcomponents');
    }

    private function getEndpoint(string $currentUrl): string
    {
        preg_match('#/([A-Za-z]+\.ff|rest/v[^?]*)#', $currentUrl, $match);

        return $match[1] ?? '';
    }

    private function removeOxidParams(?string $queryString): string
    {
        if ($queryString === null) {
            return '';
        }

        return preg_replace('/(fnc|cl)=[A-Za-z0-9_]*&?/', '', $queryString);
    }
}
