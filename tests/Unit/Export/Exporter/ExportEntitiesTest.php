<?php

declare(strict_types=1);

namespace FactFinderTests\Unit\Export\Exporter;

use Omikron\FactFinder\Oxid\Export\Data\ArticleCollection;
use Omikron\FactFinder\Oxid\Export\Entity\DataProvider;
use Omikron\FactFinder\Oxid\Export\Exporter;
use OxidEsales\Eshop\Application\Model\Article;
use OxidEsales\Eshop\Core\Field;
use OxidEsales\Eshop\Core\Registry;
use PHPUnit\Framework\TestCase;
use Tests\Variant\Export\Data\ArticleCollectionVariant;
use Tests\Variant\Export\Field\DisplayError;
use Tests\Variant\Export\Stream\CsvVariant;

class ExportEntitiesTest extends TestCase
{
    /** @var resource */
    private $tmpfile;

    /** @var CsvVariant */
    private $stream;

    protected function setUp(): void
    {
        $this->columns = [
            'ProductNumber',
            'Master',
            'Name',
            'Short',
            'Price',
        ];
        $this->tmpfile = tmpfile();
        $this->stream = new CsvVariant($this->tmpfile);
        $this->stream->addEntity($this->columns);
    }

    public function testShouldReturnEmptyStringWhenExportingEmptyCollection()
    {
        // Given
        $products = new ArticleCollection();
        $dataProvider = new DataProvider($products);

        // When
        (new Exporter())->exportEntities($this->stream, $dataProvider, $this->columns);

        // Then
        $this->assertEquals('', stream_get_contents($this->tmpfile));
    }

    public function testShouldReturnStreamWithOneProductWhenExportingCollectionWithOneProduct()
    {
        // Given
        $article = new Article([
            'oxarticles__oxskipdiscounts' => new Field('0'),
            'oxarticles__oxtitle' => new Field('bicycle'),
            'oxarticles__oxvarname' => new Field('bicycle'),
            'oxarticles__oxshortdesc' => new Field('bicycle short description'),
            'oxarticles__oxartnum' => new Field('1500'),
            'oxarticles__oxprice' => new Field('2000.00'),
        ]);
        $products = new ArticleCollectionVariant([$article]);
        $dataProvider = new DataProvider($products);

        // When
        (new Exporter())->exportEntities($this->stream, $dataProvider, $this->columns);
        $output = $this->stream->getOutput();

        // Then
        $this->assertEquals("ProductNumber;Master;Name;Short;Price\n", $output[0]);
        $this->assertEquals("1500;1500;bicycle;\"bicycle short description\";2000.00\n", $output[1]);
    }

    public function testShouldReturnStreamWithTwoProductsWhenExportingCollectionWithTwoProducts()
    {
        // Given
        $articleOne = new Article([
            'oxarticles__oxskipdiscounts' => new Field('0'),
            'oxarticles__oxtitle' => new Field('bicycle'),
            'oxarticles__oxvarname' => new Field('bicycle'),
            'oxarticles__oxshortdesc' => new Field('bicycle short description'),
            'oxarticles__oxartnum' => new Field('1500'),
            'oxarticles__oxprice' => new Field('2000.00'),
        ]);
        $articleTwo = new Article([
            'oxarticles__oxskipdiscounts' => new Field('0'),
            'oxarticles__oxtitle' => new Field('skateboard'),
            'oxarticles__oxvarname' => new Field('skateboard'),
            'oxarticles__oxshortdesc' => new Field('skateboard short description'),
            'oxarticles__oxartnum' => new Field('1501'),
            'oxarticles__oxprice' => new Field('300.00'),
        ]);
        $products = new ArticleCollectionVariant([$articleOne, $articleTwo]);
        $dataProvider = new DataProvider($products);

        // When
        (new Exporter())->exportEntities($this->stream, $dataProvider, $this->columns);
        $output = $this->stream->getOutput();

        // Then
        $this->assertEquals("ProductNumber;Master;Name;Short;Price\n", $output[0]);
        $this->assertEquals("1500;1500;bicycle;\"bicycle short description\";2000.00\n", $output[1]);
        $this->assertEquals("1501;1501;skateboard;\"skateboard short description\";300.00\n", $output[2]);
    }

    public function testShouldThrowExceptionAndInterruptExportWhenSomeErrorOccurAndProceedWhileErrorOptionIsDisabled()
    {
        // Expect
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Sample exception message');

        // Given
        $columns = array_merge($this->columns, ['DisplayError']);
        $stream = new CsvVariant($this->tmpfile);
        $articleOne = new Article([
            'oxarticles__oxskipdiscounts' => new Field('0'),
            'oxarticles__oxtitle' => new Field('bicycle'),
            'oxarticles__oxvarname' => new Field('bicycle'),
            'oxarticles__oxshortdesc' => new Field('bicycle short description'),
            'oxarticles__oxartnum' => new Field('1500'),
            'oxarticles__oxprice' => new Field('2000.00'),
            'oxarticles__oxdisplayerror' => new Field('Sample exception message'),
        ]);
        $articleTwo = new Article([
            'oxarticles__oxskipdiscounts' => new Field('0'),
            'oxarticles__oxtitle' => new Field('skateboard'),
            'oxarticles__oxvarname' => new Field('skateboard'),
            'oxarticles__oxshortdesc' => new Field('skateboard short description'),
            'oxarticles__oxartnum' => new Field('1501'),
            'oxarticles__oxprice' => new Field('300.00'),
        ]);
        $products = new ArticleCollectionVariant([$articleOne, $articleTwo]);
        $dataProvider = new DataProvider($products, new DisplayError());

        // When & Then
        Registry::getConfig()->setConfigParam('ffIsProceedWhileError', false);
        (new Exporter())->exportEntities($stream, $dataProvider, $columns);
    }

    public function testShouldContiuneWithExportAndCreateLogInDefaultPlaceWhenSomeErrorOccur()
    {
        // Expect & Given
        $logFilename = sprintf('%s/log/fact-finder/exporter.log', SHOP_SOURCE_PATH);

        if (file_exists($logFilename)) {
            unlink($logFilename);
        }

        $columns = array_merge($this->columns, ['DisplayError']);
        $stream = new CsvVariant($this->tmpfile);
        $articleOne = new Article([
            'oxarticles__oxskipdiscounts' => new Field('0'),
            'oxarticles__oxtitle' => new Field('bicycle'),
            'oxarticles__oxvarname' => new Field('bicycle'),
            'oxarticles__oxshortdesc' => new Field('bicycle short description'),
            'oxarticles__oxartnum' => new Field('1500'),
            'oxarticles__oxprice' => new Field('2000.00'),
        ]);
        $articleTwo = new Article([
            'oxarticles__oxskipdiscounts' => new Field('0'),
            'oxarticles__oxtitle' => new Field('skateboard'),
            'oxarticles__oxvarname' => new Field('skateboard'),
            'oxarticles__oxshortdesc' => new Field('skateboard short description'),
            'oxarticles__oxartnum' => new Field('1501'),
            'oxarticles__oxprice' => new Field('300.00'),
            'oxarticles__oxdisplayerror' => new Field('Sample exception message'),
        ]);
        $articleThree = new Article([
            'oxarticles__oxskipdiscounts' => new Field('0'),
            'oxarticles__oxtitle' => new Field('stand up paddle'),
            'oxarticles__oxvarname' => new Field('stand-up-paddle'),
            'oxarticles__oxshortdesc' => new Field('stand up paddle short description'),
            'oxarticles__oxartnum' => new Field('1502'),
            'oxarticles__oxprice' => new Field('900.00'),
        ]);
        $products = new ArticleCollectionVariant([$articleOne, $articleTwo, $articleThree]);
        $dataProvider = new DataProvider($products, new DisplayError());

        // When
        Registry::getConfig()->setConfigParam('ffIsProceedWhileError', true);
        (new Exporter())->exportEntities($stream, $dataProvider, $columns);
        $output = $stream->getOutput();

        // Then
        $this->assertStringContainsString('exporter.ERROR: Sample exception message {"entity":{"ProductNumber":"1501","Name":"skateboard"}} []', file_get_contents($logFilename));
        $this->assertEquals("1500;1500;bicycle;\"bicycle short description\";2000.00;\n", $output[0]);
        $this->assertEquals("1502;1502;\"stand up paddle\";\"stand up paddle short description\";900.00;\n", $output[1]);
    }

    public function testShouldContiuneWithExportAndCreateLogInCustomPlaceWhenSomeErrorOccur()
    {
        // Expect & Given
        $customLogPath = sprintf('%s/log/fact-finder-custom', SHOP_SOURCE_PATH);
        $logFilename = sprintf('%s/fact-finder/exporter.log', $customLogPath);

        if (file_exists($logFilename)) {
            unlink($logFilename);
        }

        $columns = array_merge($this->columns, ['DisplayError']);
        $stream = new CsvVariant($this->tmpfile);
        $articleOne = new Article([
            'oxarticles__oxskipdiscounts' => new Field('0'),
            'oxarticles__oxtitle' => new Field('bicycle'),
            'oxarticles__oxvarname' => new Field('bicycle'),
            'oxarticles__oxshortdesc' => new Field('bicycle short description'),
            'oxarticles__oxartnum' => new Field('1500'),
            'oxarticles__oxprice' => new Field('2000.00'),
        ]);
        $articleTwo = new Article([
            'oxarticles__oxskipdiscounts' => new Field('0'),
            'oxarticles__oxtitle' => new Field('skateboard'),
            'oxarticles__oxvarname' => new Field('skateboard'),
            'oxarticles__oxshortdesc' => new Field('skateboard short description'),
            'oxarticles__oxartnum' => new Field('1501'),
            'oxarticles__oxprice' => new Field('300.00'),
            'oxarticles__oxdisplayerror' => new Field('Sample exception message'),
        ]);
        $articleThree = new Article([
            'oxarticles__oxskipdiscounts' => new Field('0'),
            'oxarticles__oxtitle' => new Field('stand up paddle'),
            'oxarticles__oxvarname' => new Field('stand-up-paddle'),
            'oxarticles__oxshortdesc' => new Field('stand up paddle short description'),
            'oxarticles__oxartnum' => new Field('1502'),
            'oxarticles__oxprice' => new Field('900.00'),
        ]);
        $products = new ArticleCollectionVariant([$articleOne, $articleTwo, $articleThree]);
        $dataProvider = new DataProvider($products, new DisplayError());

        // When
        Registry::getConfig()->setConfigParam('ffIsProceedWhileError', true);
        Registry::getConfig()->setConfigParam('ffLogPath', $customLogPath);
        (new Exporter())->exportEntities($stream, $dataProvider, $columns);
        $output = $stream->getOutput();

        // Then
        $this->assertStringContainsString('exporter.ERROR: Sample exception message {"entity":{"ProductNumber":"1501","Name":"skateboard"}} []', file_get_contents($logFilename));
        $this->assertEquals("1500;1500;bicycle;\"bicycle short description\";2000.00;\n", $output[0]);
        $this->assertEquals("1502;1502;\"stand up paddle\";\"stand up paddle short description\";900.00;\n", $output[1]);
    }
}
