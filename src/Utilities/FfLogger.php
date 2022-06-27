<?php

declare(strict_types=1);

namespace Omikron\FactFinder\Oxid\Utilities;

use DateTimeZone;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use OxidEsales\Eshop\Core\Registry;

class FfLogger extends Logger
{
    public function __construct(string $name, array $handlers = [], array $processors = [], ?DateTimeZone $timezone = null)
    {
        parent::__construct($name, $handlers, $processors, $timezone);

        $logPath = strval(Registry::getConfig()->getConfigParam('ffLogPath'));
        $logPath = $logPath === '' ? sprintf('%slog', OX_BASE_PATH) : $logPath;
        $this->pushHandler(new StreamHandler(sprintf('%s/fact-finder/%s.log', $logPath, $name)));
    }
}
