<?php

declare(strict_types=1);

namespace Omikron\FactFinder\Oxid\Model\Export;

use Omikron\FactFinder\Oxid\Model\Config\FtpParams;
use SplFileObject as File;

class FtpClient
{
    /** @var FtpParams */
    private $params;

    public function __construct(FtpParams $params)
    {
        $this->params = $params;
    }

    public function upload(File $handle, string $filename = '')
    {
        $connection = $this->connect($this->params);
        try {
            $handle->fpassthru();
            $handle->rewind();
            ftp_fput($connection, $filename ?: $handle->getFilename(), fopen($handle, '+r'), FTP_ASCII);
        } finally {
            $this->close($connection);
        }
    }

    private function connect(FtpParams $params, int $timeout = 30)
    {
        $connection = $params->useSsl() ?
            @ftp_ssl_connect($params->getHost(), $params->getPort(), $timeout) :
            @ftp_connect($params->getHost(), $params->getPort(), $timeout);

        if (!$connection) {
            throw new \Exception('FTP connection failed to open');
        }

        if (!@ftp_login($connection, $params->getUser(), $params->getPassword())) {
            $this->close($connection);
            throw new \Exception('The FTP username or password is invalid. Verify both and try again.');
        }
        if (!@ftp_pasv($connection, true)) {
            $this->close($connection);
            throw new \Exception('The file transfer mode is invalid. Verify and try again.');
        }

        return $connection;
    }

    private function close($connection)
    {
        @ftp_close($connection);
    }
}
