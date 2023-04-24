<?php

declare(strict_types=1);

namespace FactFinderTests\Unit\Export\SuggestCategoryFeed;

use Omikron\FactFinder\Oxid\Export\SuggestCategoryFeed;
use PHPUnit\Framework\TestCase;
use FactFinderTests\Variant\Export\Stream\CsvVariant;

class GenerateTest extends TestCase
{
    /** @var CsvVariant */
    private $stream;

    protected function setUp(): void
    {
        $this->tmpfile = tmpfile();
        $this->stream = new CsvVariant($this->tmpfile);
    }

    public function testShouldReturnStreamWithDefaultColumns()
    {
        // Given
        $feed = new SuggestCategoryFeed();

        // When
        $feed->generate($this->stream);

        // Then
        $this->assertEquals("Id;Name;CategoryPath;SourceField;ParentCategory;Deeplink\n", $this->stream->getOutput()[0]);
    }
}
