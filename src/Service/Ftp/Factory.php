<?php

declare(strict_types=1);

namespace Omikron\FactFinder\Oxid\Service\Ftp;

use Omikron\FactFinder\Oxid\Model\Config\FtpParams;

class Factory
{
    /** @var FtpParams */
    private $ftpParams;

    public function __construct(FtpParams $ftpParams)
    {
        $this->ftpParams = $ftpParams;
    }

    public function create(FtpParams $params = null): AbstractClient
    {
        $ftpParams = $params ?? $this->ftpParams;
        $type      = oxNew(FtpClient::class, oxNew((get_class($ftpParams))));
        if ($params->getFtpType() === 'sftp') {
            $type = $params->getFtpAuthType() === 'key' ? oxNew(SftpPublicKey::class, oxNew((get_class($ftpParams)))) : oxNew(SftpClient::class, oxNew((get_class($ftpParams))));
        }

        return $type;
    }
}
