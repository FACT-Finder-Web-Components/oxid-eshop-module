<?php

declare(strict_types=1);

namespace Omikron\FactFinder\Oxid\Model\Api\Resource;

use Guzzle\Common\Event;
use Guzzle\Http\Message\RequestInterface;
use Omikron\FactFinder\Oxid\Model\Api\Credentials;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class Authenticator implements EventSubscriberInterface
{
    /** @var string */
    private $apiVersion;

    /** @var Credentials */
    private $credentials;

    public function __construct(string $apiVersion, Credentials $credentials)
    {
        $this->apiVersion  = $apiVersion;
        $this->credentials = $credentials;
    }

    public static function getSubscribedEvents()
    {
        return ['request.before_send' => 'authenticate'];
    }

    public function authenticate(Event $event): void
    {
        /** @var RequestInterface $request */
        $request = $event['request'];
        switch ($this->apiVersion) {
            case 'ng':
                $request->setAuth(...$this->credentials->getAuth());
                break;
            default:
                $request->setHeader('Authorization', (string) $this->credentials);
                break;
        }
    }
}
