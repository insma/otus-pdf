<?php

use PHPUnit\Framework\TestCase;

use trogon\otuspdf\io\pdf\PdfDictionary;
use trogon\otuspdf\base\InvalidCallException;

final class PdfDictionaryTest extends TestCase
{
    private $pdfDictionaryClass = 'trogon\otuspdf\io\pdf\PdfDictionary';
    private $invalidCallExceptionClass = 'trogon\otuspdf\base\InvalidCallException';

    public function testCanBeCreatedFromEmptyConfig()
    {
        $this->assertInstanceOf(
            $this->pdfDictionaryClass,
            new PdfDictionary()
        );
    }

    public function testToStringReturnsValidPdfDictionaryStringForNoItems()
    {
        $pdfDictionary = new PdfDictionary();

        $this->assertEquals(
            '<< >>',
            $pdfDictionary->toString()
        );
    }
}
