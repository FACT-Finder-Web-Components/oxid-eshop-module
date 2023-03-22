<?php

declare(strict_types=1);

namespace FactFinderTests\Unit\Subscriber\AfterRequestProcessedEventSubscriber;

use Omikron\FactFinder\Oxid\Subscriber\AfterRequestProcessedEventSubscriber;
use OxidEsales\Eshop\Core\Config;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use OxidEsales\Eshop\Core\Session;
use OxidEsales\Eshop\Application\Model\User;

class hasJustLoggedInTest extends TestCase
{
    /** @var MockObject  */
    private $session;

    /** @var MockObject  */
    private $config;

    public function testShouldSetSessionVariableWhenUserIsSetAndActionLoginNoRedirect()
    {
        // Expect
        $this->config->method('getRequestParameter')->with('fnc')->willReturn('login_noredirect');
        $this->session->method('getUser')->willReturn($this->createMock(User::class));
        $this->session->expects($this->once())->method('setVariable')->with('ff_has_just_logged_in', true);

        // When & Then
        $this->subscriber->hasJustLoggedIn();
    }

    public function testShouldNotSetSessionVariableWhenUserIsNotSetAndActionActionLoginNoRedirect()
    {
        // Expect
        $this->config->method('getRequestParameter')->with('fnc')->willReturn('login_noredirect');
        $this->session->method('getUser')->willReturn(false);
        $this->session->expects($this->never())->method('setVariable')->with('ff_has_just_logged_out', true);

        // When & Then
        $this->subscriber->hasJustLoggedIn();
    }

    public function testShouldNotSetSessionVariableWhenUserIsSetAndActionActionDifferentThanLoginNoRedirect()
    {
        // Expect
        $this->config->method('getRequestParameter')->with('fnc')->willReturn('some_action');
        $this->session->method('getUser')->willReturn($this->createMock(User::class));
        $this->session->expects($this->never())->method('setVariable')->with('ff_has_just_logged_out', true);

        // When & Then
        $this->subscriber->hasJustLoggedIn();
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
