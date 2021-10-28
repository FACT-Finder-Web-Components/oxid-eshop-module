<?php

declare(strict_types=1);

namespace Omikron\FactFinder\Oxid\Model\Export;

use Omikron\FactFinder\Oxid\Model\Config\FtpParams;

class UploadFactory
{
    public static function create(array $overrides = []): UploadInterface
    {
        $params = oxNew(FtpParams::class);
        $params->setOverrides($overrides);
        return $params->getType() === 'ftp' ? oxNew(FtpClient::class, $params) : oxNew(SftpClient::class, $params);
    }
}
