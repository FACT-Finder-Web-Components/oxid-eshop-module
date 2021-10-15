<?php

declare(strict_types=1);

namespace Omikron\FactFinder\Oxid\Service\Ftp;

use Omikron\FactFinder\Oxid\Model\Config\FtpParams;

abstract class AbstractClient
{
    protected $client;

    /** @var FtpParams */
    protected $params;

    public function __construct(FtpParams $params)
    {
        $this->params = $params;
    }

    abstract public function open(): void;

    abstract public function createDirectory(string $name): void;

    abstract public function writeFile(string $name, $content): void;
}
