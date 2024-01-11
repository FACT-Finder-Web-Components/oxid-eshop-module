<?php

declare(strict_types=1);

namespace Omikron\FactFinder\Oxid\Subscriber;

use DateTime;
use Exception;
use OxidEsales\Eshop\Core\Config;
use OxidEsales\Eshop\Core\Registry;
use OxidEsales\Eshop\Core\Session;
use OxidEsales\EshopCommunity\Internal\Container\ContainerFactory;
use OxidEsales\EshopCommunity\Internal\Framework\Module\Facade\ModuleSettingServiceInterface;
use OxidEsales\EshopCommunity\Internal\Transition\ShopEvents\BeforeHeadersSendEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class BeforeHeadersSendEventSubscriber implements EventSubscriberInterface
{
    public const HAS_JUST_LOGGED_IN  = 'ff_has_just_logged_in';

    public const HAS_JUST_LOGGED_OUT = 'ff_has_just_logged_out';

    public const USER_ID_COOKIE      = 'ff_user_id';

    private bool $isTriggered = false;

    private ModuleSettingServiceInterface $moduleSettingService;

    public function __construct(private ?Session $session = null)
    {
        $this->session              = $session ?? Registry::getSession();
        $this->moduleSettingService = ContainerFactory::getInstance()
            ->getContainer()
            ->get(ModuleSettingServiceInterface::class);
    }

    public static function getSubscribedEvents(): array
    {
        return [
            BeforeHeadersSendEvent::class => [
                ['hasJustLoggedIn'],
                ['hasJustLoggedOut'],
                ['setIsTriggered'],
            ],
        ];
    }

    public function hasJustLoggedIn(BeforeHeadersSendEvent $event): void
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

        if (
            (bool) $this->session->getVariable(self::HAS_JUST_LOGGED_IN) === true
            && $user
        ) {
            $this->setCookie(self::HAS_JUST_LOGGED_IN, '1');
            $userId = $user->getId();
            $this->setCookie(
                self::USER_ID_COOKIE,
                $this->moduleSettingService->getBoolean('ffAnonymizeUserId', 'ffwebcomponents') ? md5($userId) : $userId
            );
            $this->session->setVariable(self::HAS_JUST_LOGGED_IN, false);
        }
    }

    public function hasJustLoggedOut(BeforeHeadersSendEvent $event): void
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
