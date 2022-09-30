<?php

declare(strict_types=1);

namespace Omikron\FactFinder\Oxid\Subscriber;

use OxidEsales\Eshop\Core\Registry;
use OxidEsales\Eshop\Core\Session;
use OxidEsales\EshopCommunity\Internal\Framework\Event\AbstractShopAwareEventSubscriber;
use OxidEsales\EshopCommunity\Internal\Transition\ShopEvents\AfterRequestProcessedEvent;
use Symfony\Component\EventDispatcher\Event;

class AfterRequestProcessedEventSubscriber extends AbstractShopAwareEventSubscriber
{
    /** @var Session */
    private $session;

    public function __construct()
    {
        $this->session = Registry::getSession();
    }
    public static function getSubscribedEvents()
    {
        return [
            AfterRequestProcessedEvent::NAME => [
                ['hasJustLoggedIn'],
                ['hasJustLoggedOut'],
            ],
        ];
    }

    public function hasJustLoggedIn(Event $event): void
    {
        $user = $this->session->getUser();

        if (
            Registry::getConfig()->getRequestParameter('fnc') === 'login_noredirect'
            && $user !== null
        ) {
            $this->session->setVariable(BeforeHeadersSendEventSubscriber::HAS_JUST_LOGGED_IN, true);
        }
    }

    public function hasJustLoggedOut(Event $event): void
    {
        $user = $this->session->getUser();

        if (
            Registry::getConfig()->getRequestParameter('fnc') === 'logout'
            && $user === null
        ) {
            $this->session->setVariable(BeforeHeadersSendEventSubscriber::HAS_JUST_LOGGED_OUT, true);
        }
    }
}
