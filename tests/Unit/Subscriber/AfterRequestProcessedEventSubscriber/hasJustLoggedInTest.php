<?php

declare(strict_types=1);

namespace FactFinderTests\Unit\Subscriber\AfterRequestProcessedEventSubscriber;

use Omikron\FactFinder\Oxid\Subscriber\AfterRequestProcessedEventSubscriber;
use Omikron\FactFinder\Oxid\Subscriber\BeforeHeadersSendEventSubscriber;
use OxidEsales\Eshop\Core\Config;
use OxidEsales\Eshop\Core\Request;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use OxidEsales\Eshop\Core\Session;
use OxidEsales\Eshop\Application\Model\User;

class hasJustLoggedInTest extends TestCase
{
    /** @var Request|MockObject  */
    private $request;

    /** @var Session|MockObject  */
    private $session;

    /** @var Config|MockObject  */
    private $config;

    public function testShouldSetSessionVariableWhenUserIsSetAndActionLoginNoRedirect()
    {
        // Expect
        $this->config->method('getRequestParameter')->with('fnc')->willReturn('login_noredirect');
        $this->session->method('getUser')->willReturn($this->createMock(User::class));
        $this->session->expects($this->once())->method('setVariable')->with(BeforeHeadersSendEventSubscriber::HAS_JUST_LOGGED_IN, true);

        // When & Then
        $this->subscriber->hasJustLoggedIn();
    }

    public function testShouldNotSetSessionVariableWhenUserIsNotSetAndActionActionLoginNoRedirect()
    {
        // Expect
        $this->config->method('getRequestParameter')->with('fnc')->willReturn('login_noredirect');
        $this->session->method('getUser')->willReturn(false);
        $this->session->expects($this->never())->method('setVariable')->with(BeforeHeadersSendEventSubscriber::HAS_JUST_LOGGED_OUT, true);

        // When & Then
        $this->subscriber->hasJustLoggedIn();
    }

    public function testShouldNotSetSessionVariableWhenUserIsSetAndActionActionDifferentThanLoginNoRedirect()
    {
        // Expect
        $this->config->method('getRequestParameter')->with('fnc')->willReturn('some_action');
        $this->session->method('getUser')->willReturn($this->createMock(User::class));
        $this->session->expects($this->once())->method('setVariable')->with(BeforeHeadersSendEventSubscriber::HAS_JUST_LOGGED_IN, true);

        // When & Then
        $this->subscriber->hasJustLoggedIn();
    }

    protected function setUp(): void
    {
        $this->request = $this->createMock(Request::class);
        $this->session = $this->createMock(Session::class);
        $this->config = $this->createMock(Config::class);
        $this->subscriber = new AfterRequestProcessedEventSubscriber(
            $this->request,
            $this->session,
            $this->config
        );
    }
}
