<?php

declare(strict_types=1);

namespace Subscriber\BeforeHeadersSendEventSubscriber;

use Exception;
use Omikron\FactFinder\Oxid\Subscriber\BeforeHeadersSendEventSubscriber;
use OxidEsales\Eshop\Core\Config;
use OxidEsales\Eshop\Core\Session;
use PHPUnit\Framework\TestCase;

class HasJustLoggedOutTest extends TestCase
{
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
        $subscriber->hasJustLoggedOut();
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
        $subscriber->hasJustLoggedOut();
    }

    public function testShouldSetHasJustLoggedOutCookie()
    {
        // Expect & Given
        $subscriber = $this->getMockBuilder(BeforeHeadersSendEventSubscriber::class)
            ->setConstructorArgs([$this->session, $this->config])
            ->onlyMethods(['clearCookie', 'getCookie', 'setCookie'])
            ->getMock();
        $this->session->method('getVariable')->with('ff_has_just_logged_out')->willReturn('1');
        $this->session->method('setVariable')->with('ff_has_just_logged_out', false);
        $subscriber->expects($this->once())
                   ->method('setCookie')
                   ->with('ff_has_just_logged_out', '1');
        http_response_code(200);
        $_SERVER['HTTP_X_REQUESTED_WITH'] = null;

        // When & Then
        $subscriber->hasJustLoggedOut();
    }

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
}
