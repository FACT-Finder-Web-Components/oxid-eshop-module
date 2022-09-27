<?php

declare(strict_types=1);

namespace Omikron\FactFinder\Oxid\Subscriber;

use OxidEsales\Eshop\Application\Model\User;
use OxidEsales\Eshop\Core\Config;
use OxidEsales\Eshop\Core\Registry;
use OxidEsales\EshopCommunity\Internal\Framework\Event\AbstractShopAwareEventSubscriber;
use OxidEsales\EshopCommunity\Internal\Transition\ShopEvents\AfterRequestProcessedEvent;
use Symfony\Component\EventDispatcher\Event;

class AfterRequestProcessedEventSubscriber extends AbstractShopAwareEventSubscriber
{
    const HAS_JUST_LOGGED_IN = 'ff_has_just_logged_in';
    const HAS_JUST_LOGGED_OUT = 'ff_has_just_logged_out';
    const USER_ID_COOKIE = 'ff_user_id';

    /** @var Config */
    private $config;

    public function __construct()
    {
        $this->config = Registry::getConfig();
    }

    public static function getSubscribedEvents()
    {
        return [AfterRequestProcessedEvent::NAME => 'hasJustLoggedIn'];
    }

    public function hasJustLoggedIn(Event $event)
    {
        $user = Registry::getSession()->getUser();

        if ($user === null) {
            $this->clearCookie(self::USER_ID_COOKIE);
            $this->clearCookie(self::HAS_JUST_LOGGED_OUT);
        }

        if ((bool) $this->getCookie(self::HAS_JUST_LOGGED_IN, false) === true) {
            $this->clearCookie(self::HAS_JUST_LOGGED_IN);

            return;
        }

        if (
            $this->config->getRequestParameter('fnc') === 'login_noredirect'
            && $user !== null
        ) {
            $this->setCookie(self::HAS_JUST_LOGGED_IN, '1');
            $this->setCookie(self::USER_ID_COOKIE, $user->getId());
            Registry::getSession()->setVariable(self::HAS_JUST_LOGGED_IN, false);
        }
    }

    private function setCookie(string $name, string $value): void
    {
        setcookie(
            $name,
            $value,
            (new \DateTime())->modify('+1 hour')->getTimestamp(),
            '/'
        );
    }

    private function clearCookie(string $name): void
    {
        unset($_COOKIE[$name]);
        setcookie($name, null, -1, '/');
    }

    private function getCookie(string $name, $default = null): ?string
    {
        return $_COOKIE[$name] ?? $default;
    }
}
