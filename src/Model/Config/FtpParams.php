<?php

declare(strict_types=1);

namespace Omikron\FactFinder\Oxid\Model\Config;

use OxidEsales\Eshop\Core\Config;
use OxidEsales\Eshop\Core\Registry;

class FtpParams
{
    /** @var Config */
    private $config;

    private $overrides = [];

    public function __construct()
    {
        $this->config = Registry::getConfig();
    }

    public function getType(): string
    {
        return (string) $this->overrides['type'] ?? $this->config->getConfigParam('ffFtpType');
    }

    public function getHost(): string
    {
        return (string) $this->overrides['host'] ?? $this->config->getConfigParam('ffFtpHost');
    }

    public function getPort(): int
    {
        return (int) $this->overrides['port'] ?? $this->config->getConfigParam('ffFtpPort');
    }

    public function getUser(): string
    {
        return (string) $this->overrides['username'] ?? $this->config->getConfigParam('ffFtpUser');
    }

    public function getPassword(): string
    {
        return (string) $this->overrides['password'] ?? $this->config->getConfigParam('ffFtpPassword');
    }

    public function useSsl(): bool
    {
        return boolval($this->overrides['ssl'] ?? $this->config->getConfigParam('ffSSLEnabled'));
    }

    public function getPrivateKey(): string
    {
        return (string) trim($this->overrides['privateKey'] ?? $this->config->getConfigParam('ffFtpKey'));
    }

    public function getKeyPassphrase(): string
    {
        return (string) $this->overrides['passphrase'] ?? $this->config->getConfigParam('ffFtpKeyPassphrase');
    }

    public function toArray(): array
    {
        return [
            'host'       => $this->getHost(),
            'port'       => $this->getPort(),
            'username'   => $this->getUser(),
            'password'   => $this->getPassword(),
            'privateKey' => $this->getPrivateKey(),
            'passphrase' => $this->getKeyPassphrase(),
            'ssl'        => $this->useSsl(),
        ];
    }

    public function setOverrides(array $params): void
    {
        $this->overrides = $params;
    }
}
