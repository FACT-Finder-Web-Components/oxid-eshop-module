<?php

declare(strict_types=1);

namespace Omikron\FactFinder\Oxid\Model;

use Omikron\FactFinder\Oxid\Model\Export\Entity\Article\Collection;
use Omikron\FactFinder\Oxid\Model\Export\Entity\Article\Fields\ArticleUrl;
use Omikron\FactFinder\Oxid\Model\Export\Entity\Article\Fields\CategoryPath;
use Omikron\FactFinder\Oxid\Model\Export\Entity\Article\Fields\ImageUrl;
use Omikron\FactFinder\Oxid\Model\Export\Output\Csv;
use OxidEsales\Eshop\Core\Config;
use OxidEsales\Eshop\Core\Language;
use OxidEsales\Eshop\Core\Registry;

class ArticleFeed
{
    /** @var Collection */
    protected $articleCollection;

    /** @var Language */
    protected $language;

    /** @var Exporter */
    protected $exporter;

    /** @var Config */
    protected $config;

    /** @var string */
    protected $filenamePattern = 'factfinder_%d_%s.csv';

    public function __construct()
    {
        $this->articleCollection = oxNew(Collection::class);
        $this->exporter          = oxNew(Exporter::class);
        $this->config            = Registry::getConfig();
        $this->language          = Registry::getLang();
    }

    /**
     * @param resource $fileHandle
     */
    public function generate($fileHandle)
    {
        $output = new Csv($fileHandle);
        $output->addEntity($this->articleCollection->getFields());
        $this->exporter->export($this->articleCollection, $output, $this->getFieldModifiers());
        rewind($fileHandle);
    }

    public function getFileName(): string
    {
        return sprintf($this->filenamePattern, $this->config->getShopId(), $this->language->getLanguageAbbr());
    }

    protected function getFieldModifiers(): array
    {
        return [
            oxNew(ArticleUrl::class),
            oxNew(ImageUrl::class),
            oxNew(CategoryPath::class),
        ];
    }
}
