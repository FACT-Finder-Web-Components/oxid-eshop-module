<?php

declare(strict_types=1);

namespace Omikron\FactFinder\Oxid\Model\Config;

use OxidEsales\Eshop\Core\Config;
use OxidEsales\Eshop\Core\Registry;

class FtpParams
{
    /** @var Config */
    private $config;

    public function __construct()
    {
        $this->config = Registry::getConfig();
    }

    public function getHost(): string
    {
        return $this->config->getConfigParam('ffFtpHost');
    }

    public function getPort(): int
    {
        return (int) $this->config->getConfigParam('ffFtpPort');
    }

    public function getUser(): string
    {
        return $this->config->getConfigParam('ffFtpUser');
    }

    public function getPassword(): string
    {
        return $this->config->getConfigParam('ffFtpPassword');
    }

    public function useSsl(): bool
    {
        return boolval($this->config->getConfigParam('ffSSLEnabled'));
    }
}
