<?php

declare(strict_types=1);

namespace Omikron\FactFinder\Oxid\Model;

use Omikron\FactFinder\Oxid\Contract\Export\StreamInterface;
use Omikron\FactFinder\Oxid\Model\Export\Entity\Article\Collection;
use Omikron\FactFinder\Oxid\Model\Export\Entity\Article\Fields\ArticleUrl;
use Omikron\FactFinder\Oxid\Model\Export\Entity\Article\Fields\CategoryPath;
use Omikron\FactFinder\Oxid\Model\Export\Entity\Article\Fields\ImageUrl;
use Omikron\FactFinder\Oxid\Model\Export\Output\Csv;
use OxidEsales\Eshop\Core\Config;
use OxidEsales\Eshop\Core\Language;
use OxidEsales\Eshop\Core\Registry;
use SplFileObject as File;

class ArticleFeed
{
    /** @var Collection */
    private $articleCollection;

    /** @var StreamInterface */
    private $output;

    /** @var Language */
    private $language;

    /** @var Exporter */
    private $exporter;

    /** @var Config */
    private $config;

    /** @var string */
    private $directoryPath = '/export/factfinder/';

    /** @var string */
    private $fileName = 'factfinder.csv';

    public function __construct()
    {
        $this->articleCollection = oxNew(Collection::class);
        $this->exporter          = oxNew(Exporter::class);
        $this->config            = Registry::getConfig();
        $this->language          = Registry::getLang();
        $this->output            = new Csv();
    }

    public function generate(): File
    {
        $this->output->addEntity($this->articleCollection->getFields());
        return $this->exporter->export($this->articleCollection, $this->output, $this->getFieldModifiers());
    }

    public function getFileName(): string
    {
        $extPos = strrpos($this->fileName, '.');
        return substr($this->fileName, 0, $extPos) . '_' . $this->config->getShopId() .
            '_' . $this->language->getLanguageAbbr() . substr($this->fileName, $extPos);
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
