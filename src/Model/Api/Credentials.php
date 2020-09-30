<?php

declare(strict_types=1);

namespace Omikron\FactFinder\Oxid\Model\Api;

class Credentials
{
    /** @var string */
    private $username;

    /** @var string */
    private $password;

    /** @var string */
    private $prefix;

    /** @var string */
    private $postfix;

    /**
     * Credentials constructor.
     *
     * @param string $username
     * @param string $password
     * @param string $prefix
     * @param string $postfix
     */
    public function __construct($username, $password, $prefix, $postfix)
    {
        $this->username = $username;
        $this->password = $password;
        $this->prefix   = $prefix;
        $this->postfix  = $postfix;
    }

    public function getAuth(): array
    {
        return [$this->username, $this->password];
    }

    public function __toString(): string
    {
        $timestamp = (int) (microtime(true) * 1000);
        $password  = md5($this->prefix . $timestamp . md5($this->password) . $this->postfix);
        return sprintf('%s:%s:%s', $this->username, $password, $timestamp);
    }
}
