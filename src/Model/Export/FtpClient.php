<?php

declare(strict_types=1);

namespace Omikron\FactFinder\Oxid\Model\Export;

use League\Flysystem\Filesystem;
use League\Flysystem\Ftp\FtpAdapter;
use League\Flysystem\Ftp\FtpConnectionOptions;
use Omikron\FactFinder\Oxid\Model\Config\FtpParams;

class FtpClient implements UploadInterface
{
    /** @var FtpParams */
    private $params;

    /** @var Filesystem */
    private $connection;

    public function __construct(FtpParams $params)
    {
        $this->params = $params;
    }

    public function upload($handle, string $filename)
    {
        if (!$this->connection) {
            $this->connect($this->params);
        }
        $this->connection->writeStream($filename, $handle);
    }

    private function connect(FtpParams $params)
    {
        $this->connection = new Filesystem(new FtpAdapter(FtpConnectionOptions::fromArray($params->toArray())));
    }
}
