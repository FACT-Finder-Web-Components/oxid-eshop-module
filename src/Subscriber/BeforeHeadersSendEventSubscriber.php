<?php

declare(strict_types=1);

namespace Omikron\FactFinder\Oxid\Subscriber;

use OxidEsales\Eshop\Core\Config;
use OxidEsales\Eshop\Core\Registry;
use OxidEsales\Eshop\Core\Session;
use OxidEsales\EshopCommunity\Internal\Framework\Event\AbstractShopAwareEventSubscriber;
use OxidEsales\EshopCommunity\Internal\Transition\ShopEvents\BeforeHeadersSendEvent;
use Symfony\Component\EventDispatcher\Event;

class BeforeHeadersSendEventSubscriber extends AbstractShopAwareEventSubscriber
{
    const HAS_JUST_LOGGED_IN = 'ff_has_just_logged_in';
    const HAS_JUST_LOGGED_OUT = 'ff_has_just_logged_out';
    const USER_ID_COOKIE = 'ff_user_id';

    /** @var Config */
    private $config;

    /** @var Session */
    private $session;

    public function __construct()
    {
        $this->config = Registry::getConfig();
        $this->session = Registry::getSession();
    }

    public static function getSubscribedEvents()
    {
        return [
            BeforeHeadersSendEvent::class => [
                ['hasJustLoggedIn'],
                ['hasJustLoggedOut'],
            ],
        ];
    }

    public function hasJustLoggedIn(Event $event)
    {
        if (
            $_SERVER['HTTP_X_REQUESTED_WITH'] !== null
            || http_response_code() >= 300
        ) {
            return;
        }

        $user = $this->session->getUser();

        if (empty($user)) {
            $this->clearCookie(self::USER_ID_COOKIE);
            $this->clearCookie(self::HAS_JUST_LOGGED_OUT);
        }

        if ($this->getCookie(self::HAS_JUST_LOGGED_IN) !== '') {
            $this->clearCookie(self::HAS_JUST_LOGGED_IN);

            return;
        }

        if (
            (bool) $this->session->getVariable(self::HAS_JUST_LOGGED_IN) === true
            && $user
        ) {
            $this->setCookie(self::HAS_JUST_LOGGED_IN, '1');
            $this->setCookie(self::USER_ID_COOKIE, $user->getId());
            $this->session->setVariable(self::HAS_JUST_LOGGED_IN, false);
        }
    }

    public function hasJustLoggedOut(Event $event): void
    {
        if (
            $_SERVER['HTTP_X_REQUESTED_WITH'] !== null
            || http_response_code() >= 300
        ) {
            return;
        }

        if ((bool) $this->session->getVariable(self::HAS_JUST_LOGGED_OUT) === true) {
            $this->setCookie(self::HAS_JUST_LOGGED_OUT, '1');
            $this->clearCookie(self::USER_ID_COOKIE);
            $this->session->setVariable(self::HAS_JUST_LOGGED_OUT, false);
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
        setcookie($name, '', -1, '/');
    }

    private function getCookie(string $name): string
    {
        return $_COOKIE[$name] ?? '';
    }
}
