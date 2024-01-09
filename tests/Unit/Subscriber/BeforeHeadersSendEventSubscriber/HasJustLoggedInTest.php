<?php

declare(strict_types=1);

namespace Subscriber\BeforeHeadersSendEventSubscriber;

use Exception;
use Omikron\FactFinder\Oxid\Subscriber\BeforeHeadersSendEventSubscriber;
use OxidEsales\Eshop\Application\Model\User;
use OxidEsales\Eshop\Core\Config;
use OxidEsales\Eshop\Core\Session;
use PHPUnit\Framework\TestCase;

class HasJustLoggedInTest extends TestCase
{
    protected function setUp(): void
    {
        $this->session = $this->getMockBuilder(Session::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['setVariable', 'getUser', 'getVariable'])
            ->getMock();
        $this->config = $this->getMockBuilder(Config::class)
            ->disableOriginalConstructor()
            ->getMock();
    }

    public function testShouldNotPassRequestValidationWhenAjaxRequest()
    {
        // Expect
        $subscriber = $this->getMockBuilder(BeforeHeadersSendEventSubscriber::class)
            ->setConstructorArgs([$this->session, $this->config])
            ->onlyMethods(['validateRequest'])
            ->getMock();
        $subscriber->expects($this->once())->method('validateRequest')->willThrowException(new Exception('Not supported request'));

        // Given
        http_response_code(200);
        $_SERVER['HTTP_X_REQUESTED_WITH'] = true;

        // When & Then
        $subscriber->hasJustLoggedIn();
    }

    public function testShouldNotPassRequestValidationWhenUnsupportedResponseCode()
    {
        // Expect
        $subscriber = $this->getMockBuilder(BeforeHeadersSendEventSubscriber::class)
            ->setConstructorArgs([$this->session, $this->config])
            ->onlyMethods(['validateRequest'])
            ->getMock();
        $subscriber->expects($this->once())->method('validateRequest')->willThrowException(new Exception('Not supported request'));

        // Given
        http_response_code(301);
        $_SERVER['HTTP_X_REQUESTED_WITH'] = null;

        // When & Then
        $subscriber->hasJustLoggedIn();
    }

    public function testShouldClearUserIdCookieAndHasJustLoggedOutCookieWhenUserNotSet()
    {
        // Expect & Given
        $subscriber = $this->getMockBuilder(BeforeHeadersSendEventSubscriber::class)
            ->setConstructorArgs([$this->session, $this->config])
            ->onlyMethods(['clearCookie', 'getCookie'])
            ->getMock();
        $subscriber->expects($this->exactly(2))->method('clearCookie')->withConsecutive(
            ['ff_has_just_logged_out'],
            ['ff_user_id']
        );

        // Given
        http_response_code(200);
        $_SERVER['HTTP_X_REQUESTED_WITH'] = null;

        // When & Then
        $subscriber->hasJustLoggedIn();
    }

    public function testShouldClearHasJustLoggedInCookieWhenIsAlreadySet()
    {
        // Expect & Given
        $subscriber = $this->getMockBuilder(BeforeHeadersSendEventSubscriber::class)
            ->setConstructorArgs([$this->session, $this->config])
            ->onlyMethods(['clearCookie', 'getCookie'])
            ->getMock();
        $this->session->method('getUser')->willReturn($this->createMock(User::class));
        $subscriber->expects($this->once())->method('clearCookie')->with('ff_has_just_logged_in');
        $subscriber->method('getCookie')->with('ff_has_just_logged_in')->willReturn('1');

        // Given
        http_response_code(200);
        $_SERVER['HTTP_X_REQUESTED_WITH'] = null;

        // When & Then
        $subscriber->hasJustLoggedIn();
    }

    public function testShouldSetHasJustLoggedInCookieAndUserIdCookie()
    {
        // Expect & Given
        $subscriber = $this->getMockBuilder(BeforeHeadersSendEventSubscriber::class)
            ->setConstructorArgs([$this->session, $this->config])
            ->onlyMethods(['clearCookie', 'getCookie', 'setCookie'])
            ->getMock();
        $userId = md5('some_user_id');
        $user   = $this->createMock(User::class);
        $user->method('getId')->willReturn($userId);
        $this->session->method('getUser')->willReturn($user);
        $this->session->method('getVariable')->with('ff_has_just_logged_in')->willReturn('1');
        $this->session->method('setVariable')->with('ff_has_just_logged_in', false);
        $subscriber->expects($this->exactly(2))
                   ->method('setCookie')
                   ->withConsecutive(
                       ['ff_has_just_logged_in', '1'],
                       ['ff_user_id', $userId]
                   );
        http_response_code(200);
        $_SERVER['HTTP_X_REQUESTED_WITH'] = null;

        // When & Then
        $subscriber->hasJustLoggedIn();
    }
}
