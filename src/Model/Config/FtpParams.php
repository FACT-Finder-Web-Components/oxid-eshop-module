<?php

declare(strict_types=1);

namespace Omikron\FactFinder\Oxid\Model\Config;

use OxidEsales\Eshop\Core\Config;
use OxidEsales\Eshop\Core\Registry;
use OxidEsales\EshopCommunity\Internal\Container\ContainerFactory;
use OxidEsales\EshopCommunity\Internal\Framework\Module\Facade\ModuleSettingService;
use OxidEsales\EshopCommunity\Internal\Framework\Module\Facade\ModuleSettingServiceInterface;

class FtpParams
{
    private array $overrides = [];
    private ModuleSettingService $moduleSettingService;

    public function __construct()
    {
        $this->moduleSettingService = ContainerFactory::getInstance()
            ->getContainer()
            ->get(ModuleSettingServiceInterface::class);
    }

    public function getType(): string
    {
        return (string) ($this->overrides['type'] ?? $this->moduleSettingService->getString('ffFtpType', 'ffwebcomponents'));
    }

    public function getHost(): string
    {
        return (string) ($this->overrides['host'] ?? $this->moduleSettingService->getString('ffFtpHost', 'ffwebcomponents'));
    }

    public function getPort(): int
    {
        return (int) ($this->overrides['port'] ?? $this->moduleSettingService->getInteger('ffFtpPort', 'ffwebcomponents'));
    }

    public function getUser(): string
    {
        return (string) ($this->overrides['username'] ?? $this->moduleSettingService->getString('ffFtpUser', 'ffwebcomponents'));
    }

    public function getPassword(): string
    {
        return (string) ($this->overrides['password'] ?? $this->moduleSettingService->getString('ffFtpPassword', 'ffwebcomponents'));
    }

    public function useSsl(): bool
    {
        return boolval($this->overrides['ssl'] ?? $this->moduleSettingService->getBoolean('ffSSLEnabled', 'ffwebcomponents'));
    }

    public function getPrivateKey(): string
    {
        return trim((string) ($this->overrides['privateKey'] ?? $this->moduleSettingService->getString('ffFtpKey', 'ffwebcomponents')));
    }

    public function getRoot(): string
    {
        return (string) ($this->overrides['root'] ?? $this->moduleSettingService->getString('ffFtpRoot', 'ffwebcomponents'));
    }

    public function getKeyPassphrase(): string
    {
        return (string) ($this->overrides['passphrase'] ?? $this->moduleSettingService->getString('ffFtpKeyPassphrase', 'ffwebcomponents'));
    }

    public function toArray(): array
    {
        return [
            'host'       => $this->getHost(),
            'port'       => $this->getPort(),
            'username'   => $this->getUser(),
            'password'   => $this->getPassword(),
            'privateKey' => $this->getPrivateKey(),
            'root'       => $this->getRoot(),
            'passphrase' => $this->getKeyPassphrase(),
            'ssl'        => $this->useSsl(),
        ];
    }

    public function setOverrides(array $params): void
    {
        $this->overrides = $params;
    }
}
