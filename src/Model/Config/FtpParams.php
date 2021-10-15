<?php

declare(strict_types=1);

namespace Omikron\FactFinder\Oxid\Model\Config;

use OxidEsales\Eshop\Core\Config;
use OxidEsales\Eshop\Core\Registry;

class FtpParams
{
    /** @var Config */
    private $config;

    /** @var string */
    private $ftpType;

    /** @var string */
    private $ftpHost;

    /** @var int */
    private $ftpPort;

    /** @var string */
    private $ftpUser;

    /** @var string */
    private $ftpPassword;

    /** @var bool */
    private $sslEnabled;

    /** @var string */
    private $ftpKeyFilename;

    /** @var string */
    private $ftpKeyPassphrase;

    /** @var string */
    private $ftpAuthType;

    public function __construct()
    {
        $this->config = Registry::getConfig();
    }

    public function getFtpType(): string
    {
        $this->ftpType = $this->ftpType ?? (string) $this->config->getConfigParam('ffFtpType');
        return $this->ftpType;
    }

    public function getHost(): string
    {
        $this->ftpHost = $this->ftpHost ?? (string) $this->config->getConfigParam('ffFtpHost');
        return $this->ftpHost;
    }

    public function getPort(): int
    {
        $this->ftpPort = $this->ftpPort ?? (int) $this->config->getConfigParam('ffFtpPort');
        return $this->ftpPort;
    }

    public function getUser(): string
    {
        $this->ftpUser = $this->ftpUser ?? (string) $this->config->getConfigParam('ffFtpUser');
        return $this->ftpUser;
    }

    public function getPassword(): string
    {
        $this->ftpPassword = $this->ftpPassword ?? (string) $this->config->getConfigParam('ffFtpPassword');
        return $this->ftpPassword;
    }

    public function useSsl(): bool
    {
        $this->sslEnabled = $this->sslEnabled ?? boolval($this->config->getConfigParam('ffSSLEnabled'));
        return $this->sslEnabled;
    }

    public function getKeyFilename(): string
    {
        $this->ftpKeyFilename = !empty($this->ftpKeyFilename) ? $this->ftpKeyFilename : (string) $this->config->getConfigParam('ffFtpKeyFilename');
        return $this->ftpKeyFilename;
    }

    public function getKeyPassphrase(): string
    {
        $this->ftpKeyPassphrase = $this->ftpKeyPassphrase ?? (string) $this->config->getConfigParam('ffFtpKeyPassphrase');
        return $this->ftpKeyPassphrase;
    }

    public function getFtpAuthType(): string
    {
        $this->ftpAuthType = $this->ftpAuthType ?? (string) $this->config->getConfigParam('ffFtpAuthType');
        return $this->ftpAuthType;
    }

    /**
     * @param string $ftpType
     */
    public function setFtpType(string $ftpType): void
    {
        $this->ftpType = $ftpType;
    }

    /**
     * @param string $ftpHost
     */
    public function setFtpHost(string $ftpHost): void
    {
        $this->ftpHost = $ftpHost;
    }

    /**
     * @param int $ftpPort
     */
    public function setFtpPort(int $ftpPort): void
    {
        $this->ftpPort = $ftpPort;
    }

    /**
     * @param string $ftpUser
     */
    public function setFtpUser(string $ftpUser): void
    {
        $this->ftpUser = $ftpUser;
    }

    /**
     * @param string|null $ftpPassword
     */
    public function setFtpPassword(?string $ftpPassword = null): void
    {
        $this->ftpPassword = $ftpPassword ?? '';
    }

    /**
     * @param bool $sslEnabled
     */
    public function setSslEnabled(bool $sslEnabled): void
    {
        $this->sslEnabled = $sslEnabled;
    }

    /**
     * @param string|null $ftpKeyFilename
     */
    public function setFtpKeyFilename(?string $ftpKeyFilename = null): void
    {
        $this->ftpKeyFilename = $ftpKeyFilename ?? '';
    }

    /**
     * @param string|null $ftpKeyPassphrase
     */
    public function setFtpKeyPassphrase(?string $ftpKeyPassphrase = null): void
    {
        $this->ftpKeyPassphrase = $ftpKeyPassphrase ?? '';
    }

    /**
     * @param string $ftpAuthType
     */
    public function setFtpAuthType(string $ftpAuthType): void
    {
        $this->ftpAuthType = $ftpAuthType;
    }

    public function toArray(): array
    {
        return [
            'ftpType'          => $this->getFtpType(),
            'ftpHost'          => $this->getHost(),
            'ftpPort'          => $this->getPort(),
            'ftpUser'          => $this->getUser(),
            'ftpPassword'      => $this->getPassword(),
            'sslEnabled'       => $this->useSsl(),
            'ftpKeyFilename'   => $this->getKeyFilename(),
            'ftpKeyPassphrase' => $this->getKeyPassphrase(),
            'ftpAuthType'      => $this->getFtpAuthType(),
        ];
    }

    public function toObject(array $parametersArray): self
    {
        $methods = get_class_methods($this);
        foreach ($methods as $method) {
            if (str_contains($method, 'set')) {
                $parameterKey   = lcfirst(str_replace('set', '', $method));
                $parameterValue = $parametersArray[$parameterKey];
                $this->{$method}($parameterValue);
            }
        }

        return $this;
    }
}
