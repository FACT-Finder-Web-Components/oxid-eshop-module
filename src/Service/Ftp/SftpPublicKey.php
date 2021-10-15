<?php

declare(strict_types=1);

namespace Omikron\FactFinder\Oxid\Service\Ftp;

use Omikron\FactFinder\Oxid\Model\Config\FtpParams;
use phpseclib3\Crypt\Common\PrivateKey;
use phpseclib3\Crypt\PublicKeyLoader;
use phpseclib3\Net\SFTP;

class SftpPublicKey extends AbstractClient
{
    public function __construct(FtpParams $params)
    {
        parent::__construct($params);
        $this->client = new SFTP($params->getHost(), $params->getPort());
    }

    public function open(): void
    {
        $this->client->login($this->params->getUser(), $this->getPrivateKey());
    }

    public function createDirectory(string $name): void
    {
        $this->client->mkdir($name);
        $this->client->chdir($name);
    }

    public function writeFile(string $name, $content): void
    {
        $this->client->put($name, $content);
    }

    protected function getPrivateKey(): PrivateKey
    {
        $key = file_get_contents(__DIR__ . '../../../etc/key/' . $this->params->getKeyFilename());
        return $this->params->getKeyPassphrase() ? PublicKeyLoader::loadPrivateKey($key, $this->params->getKeyPassphrase()) : PublicKeyLoader::loadPrivateKey($key);
    }
}
