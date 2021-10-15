<?php

declare(strict_types=1);

namespace Omikron\FactFinder\Oxid\Model\Export;

use Omikron\FactFinder\Oxid\Model\Config\FtpParams;
use Omikron\FactFinder\Oxid\Service\Ftp\Factory as FtpFactory;

class FtpClient
{
    public const TEST_DIR = 'ff_test';

    public const UPLOAD_DIR = 'export';

    public const TEST_FILE = 'testconnection';

    /** @var FtpParams */
    private $ftpParams;

    /** @var FtpFactory */
    private $ftpFactory;

    public function __construct(FtpParams $ftpParams)
    {
        $this->ftpParams  = $ftpParams;
        $this->ftpFactory = oxNew(FtpFactory::class, oxNew(get_class($this->ftpParams)));
    }

    /**
     * @param $handle
     * @param string $filename
     */
    public function upload($handle, string $filename)
    {
        $client = $this->ftpFactory->create();
        $client->open();
        $client->createDirectory(self::UPLOAD_DIR);
        $client->writeFile($filename, $handle);
    }

    public function testConnection(array $requestParameters)
    {
        $client = $this->ftpFactory->create($this->ftpParams->toObject($this->prepareRequestValues($requestParameters)));
        $client->open();
        $client->createDirectory(self::TEST_DIR);
        $client->writeFile(self::TEST_FILE, 'test');
    }

    public function trimProtocol(string $host): string
    {
        preg_match('#^(?:s?ftps?)://(.+?)/?$#', $host, $match);
        return $match ? $match[1] : $host;
    }

    private function prepareRequestValues(array $requestParameters): array
    {
        if ($requestParameters['sslEnabled']) {
            $requestParameters['sslEnabled'] = filter_var($requestParameters['sslEnabled'], FILTER_VALIDATE_BOOLEAN);
        }
        if ($requestParameters['ftpPort']) {
            $requestParameters['ftpPort'] = (int) $requestParameters['ftpPort'];
        }

        if ($requestParameters['ffFtpHost']) {
            $requestParameters['ffFtpHost'] = $this->trimProtocol($requestParameters['ffFtpHost']);
        }

        return $requestParameters;
    }
}
