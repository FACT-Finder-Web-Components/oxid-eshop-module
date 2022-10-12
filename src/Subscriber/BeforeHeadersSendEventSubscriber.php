<?php

declare(strict_types=1);

namespace Omikron\FactFinder\Oxid\Subscriber;

use DateTime;
use Exception;
use OxidEsales\Eshop\Core\Config;
use OxidEsales\Eshop\Core\Registry;
use OxidEsales\Eshop\Core\Session;
use OxidEsales\EshopCommunity\Internal\Framework\Event\AbstractShopAwareEventSubscriber;
use OxidEsales\EshopCommunity\Internal\Transition\ShopEvents\BeforeHeadersSendEvent;
use Symfony\Component\EventDispatcher\Event;

class BeforeHeadersSendEventSubscriber extends AbstractShopAwareEventSubscriber
{
    public const HAS_JUST_LOGGED_IN  = 'ff_has_just_logged_in';

    public const HAS_JUST_LOGGED_OUT = 'ff_has_just_logged_out';

    public const USER_ID_COOKIE      = 'ff_user_id';

    /** @var Config */
    private $config;

    /** @var Session */
    private $session;

    /** @var bool */
    private $isTriggered = false;

    public function __construct(
        ?Session $session = null,
        ?Config $config = null
    ) {
        $this->session = $session ?? Registry::getSession();
        $this->config  = $config ?? Registry::getConfig();
    }

    public static function getSubscribedEvents()
    {
        return [
            BeforeHeadersSendEvent::NAME => [
                ['hasJustLoggedIn'],
                ['hasJustLoggedOut'],
                ['setIsTriggered'],
            ],
        ];
    }

    public function hasJustLoggedIn(): void
    {
        try {
            $this->validateRequest();
        } catch (Exception $e) {
            return;
        }

        $user = $this->session->getUser();

        if (empty($user)) {
            $this->clearCookie(self::HAS_JUST_LOGGED_OUT);
            $this->clearCookie(self::USER_ID_COOKIE);
        }

        if ($this->getCookie(self::HAS_JUST_LOGGED_IN) !== '') {
            $this->clearCookie(self::HAS_JUST_LOGGED_IN);

            return;
        }

        if ((bool) $this->session->getVariable(self::HAS_JUST_LOGGED_IN) === true) {
            $this->setCookie(self::HAS_JUST_LOGGED_IN, '1');
            $this->setCookie(self::USER_ID_COOKIE, $user->getId());
            $this->session->setVariable(self::HAS_JUST_LOGGED_IN, false);
        }
    }

    public function hasJustLoggedOut(): void
    {
        try {
            $this->validateRequest();
        } catch (Exception $e) {
            return;
        }

        if ((bool) $this->session->getVariable(self::HAS_JUST_LOGGED_OUT) === true) {
            $this->setCookie(self::HAS_JUST_LOGGED_OUT, '1');
            $this->clearCookie(self::USER_ID_COOKIE);
            $this->session->setVariable(self::HAS_JUST_LOGGED_OUT, false);
        }
    }

    public function setIsTriggered(): void
    {
        $this->isTriggered = true;
    }

    protected function setCookie(string $name, string $value): void
    {
        setcookie(
            $name,
            $value,
            (new DateTime())->modify('+1 hour')->getTimestamp(),
            '/'
        );
    }

    protected function clearCookie(string $name): void
    {
        unset($_COOKIE[$name]);
        setcookie($name, '', -1, '/');
    }

    protected function getCookie(string $name): string
    {
        return $_COOKIE[$name] ?? '';
    }

    /**
     * @throws Exception
     */
    protected function validateRequest(): void
    {
        if (
            $this->isTriggered
            || $_SERVER['HTTP_X_REQUESTED_WITH'] !== null
            || http_response_code() >= 300
        ) {
            throw new Exception('Not supported request');
        }
    }
}
