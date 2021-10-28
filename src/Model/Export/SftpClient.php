<?php

declare(strict_types=1);

namespace Omikron\FactFinder\Oxid\Model\Export;

use League\Flysystem\Filesystem;
use League\Flysystem\PhpseclibV2\SftpAdapter;
use League\Flysystem\PhpseclibV2\SftpConnectionProvider;
use Omikron\FactFinder\Oxid\Model\Config\FtpParams;

class SftpClient implements UploadInterface
{
    /** @var FtpParams */
    private $ftpParams;

    /** @var Filesystem */
    private $connection;

    public function __construct(FtpParams $ftpParams)
    {
        $this->ftpParams = $ftpParams;
    }

    public function upload($handle, string $filename)
    {
        if (!$this->connection) {
            $this->connect($this->ftpParams);
        }
        $this->connection->writeStream($filename, $handle);
    }

    private function connect(FtpParams $params)
    {
        $this->connection = new Filesystem(new SftpAdapter(SftpConnectionProvider::fromArray($params->toArray()), $params->getRoot()));
    }
}
