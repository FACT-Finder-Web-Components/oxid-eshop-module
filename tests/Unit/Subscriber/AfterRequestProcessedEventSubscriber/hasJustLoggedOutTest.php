<?php

declare(strict_types=1);

namespace FactFinderTests\Unit\Subscriber\AfterRequestProcessedEventSubscriber;

use Omikron\FactFinder\Oxid\Subscriber\AfterRequestProcessedEventSubscriber;
use Omikron\FactFinder\Oxid\Subscriber\BeforeHeadersSendEventSubscriber;
use OxidEsales\Eshop\Core\Config;
use OxidEsales\Eshop\Core\Request;
use OxidEsales\Eshop\Core\Session;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class hasJustLoggedOutTest extends TestCase
{
    /** @var Request|MockObject  */
    private $request;

    /** @var Session|MockObject  */
    private $session;

    /** @var Config|MockObject  */
    private $config;

    protected function setUp(): void
    {
        $this->request    = $this->createMock(Request::class);
        $this->session    = $this->createMock(Session::class);
        $this->config     = $this->createMock(Config::class);
        $this->subscriber = new AfterRequestProcessedEventSubscriber(
            $this->request,
            $this->session,
            $this->config
        );
    }

    public function testShouldSetSessionVariableWhenUserIsSetToFalseAndActionLogout()
    {
        // Expect
        $this->request->method('getRequestParameter')->with('fnc')->willReturn('logout');
        $this->session->method('getUser')->willReturn(false);
        $this->session->expects($this->once())->method('setVariable')->with(BeforeHeadersSendEventSubscriber::HAS_JUST_LOGGED_OUT, true);

        // When & Then
        $this->subscriber->hasJustLoggedOut();
    }

    public function testShouldSetSessionVariableWhenUserIsSetToNullAndActionLogout()
    {
        // Expect
        $this->request->method('getRequestParameter')->with('fnc')->willReturn('logout');
        $this->session->method('getUser')->willReturn(null);
        $this->session->expects($this->once())->method('setVariable')->with(BeforeHeadersSendEventSubscriber::HAS_JUST_LOGGED_OUT, true);

        // When & Then
        $this->subscriber->hasJustLoggedOut();
    }

    public function testShouldSetSessionVariableWhenUserIsSetToEmptyStringAndActionLogout()
    {
        // Expect
        $this->request->method('getRequestParameter')->with('fnc')->willReturn('logout');
        $this->session->method('getUser')->willReturn(null);
        $this->session->expects($this->once())->method('setVariable')->with(BeforeHeadersSendEventSubscriber::HAS_JUST_LOGGED_OUT, true);

        // When & Then
        $this->subscriber->hasJustLoggedOut();
    }

    public function testShouldNotSetSessionVariableWhenUserIsNotSetAndActionDifferentThanLogout()
    {
        // Expect
        $this->request->method('getRequestParameter')->with('fnc')->willReturn('some_action');
        $this->session->method('getUser')->willReturn(false);
        $this->session->expects($this->never())->method('setVariable')->with(BeforeHeadersSendEventSubscriber::HAS_JUST_LOGGED_OUT, true);

        // When & Then
        $this->subscriber->hasJustLoggedOut();
    }
}
