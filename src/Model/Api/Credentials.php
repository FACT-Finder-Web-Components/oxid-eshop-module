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

    public function toArray(): array
    {
        $timestamp = (int) (microtime(true) * 1000);
        return [
            'timestamp' => $timestamp,
            'username'  => $this->username,
            'password'  => md5($this->prefix . $timestamp . md5($this->password) . $this->postfix), // phpcs:ignore
        ];
    }

    public function __toString(): string
    {
        return http_build_query($this->toArray());
    }
}
