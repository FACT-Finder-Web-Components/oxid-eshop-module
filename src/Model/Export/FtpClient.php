<?php

declare(strict_types=1);

namespace Omikron\FactFinder\Oxid\Model\Export;

use Exception;
use Omikron\FactFinder\Oxid\Model\Config\FtpParams;

class FtpClient
{
    private const FTP_PROTOCOL_PREFIX = 'ftp://';

    /** @var FtpParams */
    private $params;

    public function __construct(FtpParams $params)
    {
        $this->params = $params;
    }

    /**
     * @param resource $handle
     * @param string   $filename
     *
     * @throws Exception
     */
    public function upload($handle, string $filename)
    {
        try {
            $connection = $this->connect($this->params);
            rewind($handle);
            ftp_fput($connection, $filename, $handle, FTP_ASCII);
        } finally {
            if (isset($connection) && $connection) {
                ftp_close($connection);
            }
        }
    }

    private function connect(FtpParams $params, int $timeout = 30)
    {
        $ftpHost    = ltrim($params->getHost(), self::FTP_PROTOCOL_PREFIX);
        $connection = $params->useSsl() ?
            ftp_ssl_connect($ftpHost, $params->getPort(), $timeout) :
            ftp_connect($ftpHost, $params->getPort(), $timeout);

        if (!$connection) {
            throw new Exception('FTP connection failed to open');
        }

        if (!ftp_login($connection, $params->getUser(), $params->getPassword())) {
            throw new Exception('The FTP username or password is invalid. Verify both and try again.');
        }

        if (!ftp_pasv($connection, true)) {
            throw new Exception('The file transfer mode is invalid. Verify and try again.');
        }

        return $connection;
    }
}
