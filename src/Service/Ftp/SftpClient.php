<?php

declare(strict_types=1);

namespace Omikron\FactFinder\Oxid\Service\Ftp;

use Omikron\FactFinder\Oxid\Model\Config\FtpParams;
use phpseclib3\Net\SFTP;

class SftpClient extends AbstractClient
{
    public function __construct(FtpParams $params)
    {
        parent::__construct($params);
        $this->client = new SFTP($this->params->getHost());
    }

    public function open(): void
    {
        $this->client->login($this->params->getUser(), $this->params->getPassword());
    }

    public function createDirectory(string $name): void
    {
        $this->client->mkdir($name, 0777, true);
        $this->client->chdir($name);
    }

    public function writeFile(string $name, $content): void
    {
        $this->client->put($name, $content);
    }
}
