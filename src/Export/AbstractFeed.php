<?php

declare(strict_types=1);

namespace Omikron\FactFinder\Oxid\Export;

use Omikron\FactFinder\Oxid\Export\Stream\StreamInterface;
use OxidEsales\Eshop\Core\Registry;
use ReflectionClass;

abstract class AbstractFeed
{
    /** @var string[] */
    protected $columns;

    abstract public function generate(StreamInterface $stream): void;

    public function getFileName(): string
    {
        $slug = strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', (new ReflectionClass($this))->getShortName()));
        return sprintf('export.%s.%s.csv', $slug, $this->getChannel(Registry::getLang()->getLanguageAbbr()));
    }

    abstract protected function getAdditionalFields(): array;

    protected function getChannel(string $lang): string
    {
        return Registry::getConfig()->getConfigParam('ffChannel')[$lang];
    }
}
