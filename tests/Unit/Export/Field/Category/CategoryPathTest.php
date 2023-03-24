<?php

declare(strict_types=1);

namespace FactFinderTests\Unit\Export\Field\Category;

use FactFinderTests\Variant\Export\Model\CategoryVariant;
use Omikron\FactFinder\Oxid\Export\Field\Category\CategoryPath;
use OxidEsales\Eshop\Core\Field;
use PHPUnit\Framework\TestCase;

class CategoryPathTest extends TestCase
{
    public function testShouldReturnName()
    {
        // When & Then
        $this->assertEquals('CategoryPath', (new CategoryPath())->getName());
    }

    public function testShouldReturnValueForTopCategory()
    {
        // Given
        $category = new CategoryVariant([
            'oxcategories__oxtitle' => new Field('Gear'),
            'oxcategories__oxparentid' => new Field('oxrootid'),
        ]);

        // When
        $value = (new CategoryPath())->getValue($category, $category);

        // Then
        $this->assertEquals("Gear", $value);
    }

    public function testShouldReturnValueForSecondLevelCategory()
    {
        // Given
        $category = new CategoryVariant([
            'oxcategories__oxtitle' => new Field('Gear'),
            'oxcategories__oxparentid' => new Field('oxrootid'),
        ]);
        $category2 = new CategoryVariant([
            'oxcategories__oxtitle' => new Field('Fashion'),
            'oxcategories__oxparentid' => new Field('30e44ab83fdee7564.23264141'),
        ]);
        $category2->setParentCategory($category);

        // When
        $value = (new CategoryPath())->getValue($category2, $category);

        // Then
        $this->assertEquals("Gear/Fashion", $value);
    }

    public function testShouldReturnValueForFourthLevelCategory()
    {
        // Given
        $category = new CategoryVariant([
            'oxcategories__oxtitle' => new Field('Gear'),
            'oxcategories__oxparentid' => new Field('oxrootid'),
        ]);
        $category2 = new CategoryVariant([
            'oxcategories__oxtitle' => new Field('Fashion'),
            'oxcategories__oxparentid' => new Field('30e44ab83fdee7564.23264141'),
        ]);
        $category2->setParentCategory($category);
        $category3 = new CategoryVariant([
            'oxcategories__oxtitle' => new Field('For Him'),
            'oxcategories__oxparentid' => new Field('fad181ad64642b955becd0759345161e'),
        ]);
        $category3->setParentCategory($category2);
        $category4 = new CategoryVariant([
            'oxcategories__oxtitle' => new Field('Shirts & Co.'),
            'oxcategories__oxparentid' => new Field('fad7facadcb7d4297f033d242aa0d310'),
        ]);
        $category4->setParentCategory($category3);

        // When
        $value = (new CategoryPath())->getValue($category4, $category3);

        // Then
        $this->assertEquals("Gear/Fashion/For%20Him/Shirts%20%26%20Co.", $value);
    }
}
