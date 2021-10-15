<?php

declare(strict_types=1);

namespace Omikron\FactFinder\Oxid\Service\Ftp;

use FtpClient\FtpClient as FtpClientBase;
use Omikron\FactFinder\Oxid\Model\Config\FtpParams;

class FtpClient extends AbstractClient
{
    public function __construct(FtpParams $params)
    {
        parent::__construct($params);
        $this->client = oxNew(FtpClientBase::class);
    }

    public function open(): void
    {
        $this->client->connect($this->params->getHost(), $this->params->useSsl(), $this->params->getPort());
        $this->client->login($this->params->getUser(), $this->params->getPassword());
    }

    public function createDirectory(string $name): void
    {
        $this->client->mkdir($name);
        $this->client->chdir($name);
    }

    public function writeFile(string $name, $content): void
    {
        $this->client->putFromString($name, $content);
    }
}
