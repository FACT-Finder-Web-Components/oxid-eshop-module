<?php

declare(strict_types=1);

namespace Omikron\FactFinder\Oxid\Model\Export;

use League\Flysystem\Filesystem;
use League\Flysystem\PhpseclibV2\SftpAdapter;
use League\Flysystem\PhpseclibV2\SftpConnectionProvider;
use Omikron\FactFinder\Oxid\Model\Config\FtpParams;

class SftpClient implements UploadInterface
{
    private Filesystem $connection;

    public function __construct(FtpParams $ftpParams)
    {
        $this->connection = new Filesystem(
            new SftpAdapter(SftpConnectionProvider::fromArray($ftpParams->toArray()), $ftpParams->getRoot())
        );
    }

    public function upload($handle, string $filename)
    {
        $this->connection->writeStream($filename, $handle);
    }
}
