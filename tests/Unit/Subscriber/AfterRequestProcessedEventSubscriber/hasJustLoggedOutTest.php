<?php

declare(strict_types=1);

namespace Tests\Unit\Subscriber\AfterRequestProcessedEventSubscriber;

use Omikron\FactFinder\Oxid\Subscriber\AfterRequestProcessedEventSubscriber;
use OxidEsales\Eshop\Core\Config;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use OxidEsales\Eshop\Core\Session;

class hasJustLoggedOutTest extends TestCase
{
    /** @var MockObject  */
    private $session;

    /** @var MockObject  */
    private $config;

    public function testShouldSetSessionVariableWhenUserIsSetToFalseAndActionLogout()
    {
        // Expect
        $this->config->method('getRequestParameter')->with('fnc')->willReturn('logout');
        $this->session->method('getUser')->willReturn(false);
        $this->session->expects($this->once())->method('setVariable')->with('ff_has_just_logged_out', true);

        // When & Then
        $this->subscriber->hasJustLoggedOut();
    }

    public function testShouldSetSessionVariableWhenUserIsSetToNullAndActionLogout()
    {
        // Expect
        $this->config->method('getRequestParameter')->with('fnc')->willReturn('logout');
        $this->session->method('getUser')->willReturn(null);
        $this->session->expects($this->once())->method('setVariable')->with('ff_has_just_logged_out', true);

        // When & Then
        $this->subscriber->hasJustLoggedOut();
    }

    public function testShouldSetSessionVariableWhenUserIsSetToEmptyStringAndActionLogout()
    {
        // Expect
        $this->config->method('getRequestParameter')->with('fnc')->willReturn('logout');
        $this->session->method('getUser')->willReturn(null);
        $this->session->expects($this->once())->method('setVariable')->with('ff_has_just_logged_out', true);

        // When & Then
        $this->subscriber->hasJustLoggedOut();
    }

    public function testShouldNotSetSessionVariableWhenUserIsNotSetAndActionDifferentThanLogout()
    {
        // Expect
        $this->config->method('getRequestParameter')->with('fnc')->willReturn('some_action');
        $this->session->method('getUser')->willReturn(false);
        $this->session->expects($this->never())->method('setVariable')->with('ff_has_just_logged_out', true);

        // When & Then
        $this->subscriber->hasJustLoggedOut();
    }

    protected function setUp(): void
    {
        $this->session = $this->getMockBuilder(Session::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['setVariable', 'getUser'])
            ->getMock();
        $this->config = $this->getMockBuilder(Config::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->subscriber = new AfterRequestProcessedEventSubscriber($this->session, $this->config);
    }
}
