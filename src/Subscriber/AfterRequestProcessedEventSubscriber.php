<?php

declare(strict_types=1);

namespace Omikron\FactFinder\Oxid\Subscriber;

use OxidEsales\Eshop\Core\Config;
use OxidEsales\Eshop\Core\Registry;
use OxidEsales\Eshop\Core\Request;
use OxidEsales\Eshop\Core\Session;
use OxidEsales\EshopCommunity\Internal\Framework\Event\AbstractShopAwareEventSubscriber;
use OxidEsales\EshopCommunity\Internal\Transition\ShopEvents\AfterRequestProcessedEvent;

class AfterRequestProcessedEventSubscriber extends AbstractShopAwareEventSubscriber
{
    /** @var Request */
    private $request;

    /** @var Session */
    private $session;

    /** @var Config */
    private $config;

    public function __construct(
        ?Request $request = null,
        ?Session $session = null,
        ?Config $config = null
    ) {
        $this->request = $request ?? Registry::getRequest();
        $this->session = $session ?? Registry::getSession();
        $this->config  = $config ?? Registry::getConfig();
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

    public function hasJustLoggedIn(): void
    {
        $user = $this->session->getUser();

        if (!empty($user)) {
            $this->session->setVariable(BeforeHeadersSendEventSubscriber::HAS_JUST_LOGGED_IN, true);
        }
    }

    public function hasJustLoggedOut(): void
    {
        $user = $this->session->getUser();

        if (empty($user) && $this->request->getRequestParameter('fnc') === 'logout') {
            $this->session->setVariable(BeforeHeadersSendEventSubscriber::HAS_JUST_LOGGED_OUT, true);
        }
    }
}
