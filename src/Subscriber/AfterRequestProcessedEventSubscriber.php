<?php

declare(strict_types=1);

namespace Omikron\FactFinder\Oxid\Subscriber;

use OxidEsales\Eshop\Core\Config;
use OxidEsales\Eshop\Core\Registry;
use OxidEsales\Eshop\Core\Request;
use OxidEsales\Eshop\Core\Session;
use OxidEsales\EshopCommunity\Internal\Transition\ShopEvents\AfterRequestProcessedEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class AfterRequestProcessedEventSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private ?Request $request = null,
        private ?Session $session = null,
    ) {
        $this->request = $request ?? Registry::getRequest();
        $this->session = $session ?? Registry::getSession();
    }

    public static function getSubscribedEvents(): array
    {
        return [
            AfterRequestProcessedEvent::class => [
                ['hasJustLoggedIn'],
                ['hasJustLoggedOut'],
            ],
        ];
    }

    public function hasJustLoggedIn(AfterRequestProcessedEvent $event): void
    {
        $user = $this->session->getUser();

        if (!empty($user)) {
            $this->session->setVariable(BeforeHeadersSendEventSubscriber::HAS_JUST_LOGGED_IN, true);
        }
    }

    public function hasJustLoggedOut(AfterRequestProcessedEvent $event): void
    {
        $user = $this->session->getUser();

        if (empty($user) && $this->request->getRequestParameter('fnc') === 'logout') {
            $this->session->setVariable(BeforeHeadersSendEventSubscriber::HAS_JUST_LOGGED_OUT, true);
        }
    }
}
